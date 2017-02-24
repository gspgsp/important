package org.magicwall.bolt;

import org.apache.storm.task.OutputCollector;
import org.apache.storm.task.TopologyContext;
import org.apache.storm.topology.OutputFieldsDeclarer;
import org.apache.storm.topology.base.BaseRichBolt;
import org.apache.storm.tuple.Fields;
import org.apache.storm.tuple.Tuple;
import org.apache.storm.tuple.Values;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import java.sql.*;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.Map;

/**
 * 这里是利用关注来更新牌号信息
 * 信息输入：user_id:focus_id[:*]:(A|D|F)   其中A为新增;D为删除;F为刷新;如果F，则foucus_id采用*占位
 * 处理流程：
 * 1.D清除用户对应关注人的关注牌号；F清除用户所有关注人的关注牌号
 * 2.A新增用户对应关注人的关注牌号；F：查找所有关注人的关注牌号，并且新增，注意：查重以后[排除自己的牌号]，；
 * 3.将更新的牌号发送出去，格式：user_id:model_id:*:foucus_id:do,
 *                         第三个*表示BUY+SELL全部推荐, do支持A/D/F
 *                         本案仅仅支持A/D，不支持F
 */
public class FansBolt extends BaseRichBolt {

    private OutputCollector collector;

    private static final Logger log = LoggerFactory.getLogger(FansBolt.class);

    // MySQL
    final String url;
    final String driver;
    final String username;
    final String password;
    Connection connection;

    public FansBolt(String url, String driver, String username, String password) {
        this.url = url;
        this.driver = driver;
        this.username = username;
        this.password = password;
    }

    @Override
    public void prepare(Map map, TopologyContext topologyContext, OutputCollector outputCollector) {
        this.collector = collector;
        //加载驱动程序
        try {
            Class.forName(this.driver);
            //建立连接
            this.connection = DriverManager.getConnection(url, this.username, this.password);
        } catch (ClassNotFoundException e) {
            e.printStackTrace();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    /**
     * 执行部分
     * @param input         输入数据
     */
    @Override
    public void execute(Tuple input) {
        String product = input.getStringByField("fansspout");
        String[] detail = product.split(":");
        int userId = Integer.parseInt(detail[0]);   // 关注人的ID
        int focusId = Integer.parseInt(detail[1]);  // 被关注人的ID，如果F则采用*占位
        String todo = detail[2];                    // 操作A/D分别为增加与删除,F为刷新

        // 从数据库里面查询
        // 1.D清除用户对应关注人的关注牌号；F清除用户所有关注人的关注牌号
        int uRows = 0;
        PreparedStatement ps= null;
        // 先删除
        if(todo.equals("D")){
            try {
                ps = this.connection.prepareStatement("DELETE FROM p2p_suggestion_model where `type`='FRIEND' and user_id=? and friend_id=?");
                ps.setInt(1,userId);
                ps.setInt(2,focusId);
                uRows = ps.executeUpdate();
            }
            catch (SQLException sqle) {
                sqle.printStackTrace();
            }
        }
        else if(todo.equals("F")) {
            try {
                ps = this.connection.prepareStatement("DELETE FROM p2p_suggestion_model where `type`='FRIEND' and user_id=?");
                ps.setInt(1,userId);
                uRows = ps.executeUpdate();
            }
            catch (SQLException sqle) {
                sqle.printStackTrace();
            }
        }

        // 2.A新增用户对应关注人的关注牌号；F：查找所有关注人的关注牌号，并且新增；
        if(todo.equals("A") || todo.equals("F")) {
            ArrayList focusList = new ArrayList();
            if(todo.equals("F")) {
                // 0.查找用户所有的关注人
                try {
                    ps = this.connection.prepareStatement("select focused_id from p2p_weixin_fans where user_id=?");
                    ps.setInt(1,userId);
                    ResultSet rs=ps.executeQuery();

                    while(rs.next()) {
                        int focus =rs.getInt(1);
                        focusList.add(focus);
                    }
                } catch (SQLException e) {
                    e.printStackTrace();
                }
            }
            else{
                // 0.增加传递过来的关注人
                focusList.add(focusId);
            }

            // 循环追加所有的关注牌号，并且排除重复牌号
            // 0.查找自己对应的牌号，用于查重
            ArrayList userModelList = new ArrayList();
            try {
                ps = this.connection.prepareStatement("select model from p2p_suggestion_model where user_id=? and `type`=?");
                ps.setInt(1, userId);
                ps.setString(2, "ME");
                ResultSet rs=ps.executeQuery();
                while(rs.next()) {
                    String model =rs.getString(1);
                    userModelList.add(model);
                }
            } catch (SQLException e) {
                e.printStackTrace();
            }

            // 刷新一次关注，仅仅包含自己的
            List<Object> values = new Values(userId+":*:*:*:F"); // 消息给后道工序
            this.collector.emit(values);

            for(int k=0; k<focusList.size();k++) {
                ArrayList modelList = new ArrayList();
                String type = "FRIEND";

                // a.查找新增关注人对应的自己的牌号
                try {
                    ps = this.connection.prepareStatement("select model from p2p_suggestion_model where user_id=? and `type`=?");
                    ps.setInt(1, (Integer) focusList.get(k));
                    ps.setString(2,type);
                    ResultSet rs=ps.executeQuery();
                    while(rs.next()) {
                        String model =rs.getString(1);
                        // 自己没有关注，才增加
                        if(userModelList.indexOf(model)==-1){
                            modelList.add(model);
                        }
                    }
                } catch (SQLException e) {
                    e.printStackTrace();
                }

                // b.插入新的纪录
                for(int i=0; i<modelList.size(); i++) {
                    try {
                        ps = this.connection.prepareStatement("insert into p2p_suggestion_model (user_id, `type`, friend_id,  model) VALUES (?, ?, ?, ?)");
                        ps.setInt(1, userId);
                        ps.setString(2, type);
                        ps.setInt(3, (Integer) focusList.get(k));
                        ps.setString(4, (String) modelList.get(i));
                        uRows = ps.executeUpdate();
                        List<Object> addValues = new Values(userId+":"+modelList.get(i)+":*:"+focusList.get(k)+":A"); // 消息给后道工序
                        this.collector.emit(addValues);
                    }
                    catch (SQLException sqle) {
                        sqle.printStackTrace();
                    }
                    catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            }
        }
    }

    /**
     * 清理
     */
    public void cleanup() {
    }

    /**oo
     * 定义输出字段
     * @param declarer
     */
    public void declareOutputFields(OutputFieldsDeclarer declarer) {
        declarer.declare(new Fields("modelspout"));
    }
}

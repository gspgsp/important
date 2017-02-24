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
 * 这里是利用更新牌号来更新推荐信息
 * 信息输入：user_id:model_id:type:foucus_id:do   如果是整体刷新，则model_id为*;
 *                                                如果自己的牌号，则foucus_id采用*占位;
 *                                                type可选：BUY, SELL, *，其中*表示全部，目前只有*
 *                                                do可选：A/D/F,如果F的话，那么model_id,type,focus_id均为*
 * 处理流程：
 * 1.如果model_id不为*，删除旧牌号对应的推荐信息; 否则删除全部推荐信息;
 * 2.根据model_id查找推荐信息，*则需要先查找所有的牌号，然后推荐
 * 3.更新推荐列表
 */
public class ModelBolt extends BaseRichBolt {

    private OutputCollector collector;

    private static final Logger log = LoggerFactory.getLogger(ModelBolt.class);

    // MySQL
    final String url;
    final String driver;
    final String username;
    final String password;
    Connection connection;

    public ModelBolt(String url, String driver, String username, String password) {
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
        String product = input.getStringByField("modelspout");
        String[] detail = product.split(":");
        int userId = Integer.parseInt(detail[0]);
        String model = detail[1];
        int type = 0;
        if(detail[2].equals("SELL")) {
            type = 1;
        }
        else if(detail[2].equals("BUY")) {
            type = 2;
        }
        int focus = 0;
        if(!detail[3].equals("*")){
            focus = Integer.parseInt(detail[3]);
        }
        String todo = "F";               // 支持A/D/F
        if(!model.equals("*")) {
            todo = detail[4];
        }

        // 从数据库里面查询
        PreparedStatement ps= null;
        // 1.删除旧的牌号，删除旧的对应牌号的推荐信息
        // 注意D只有直接发送才有， Fans发出的只有A/F 2种
        if(todo.equals("D")) {
            // 删除用户所有的牌号的推荐信息
            if(model.equals("*")){
                try {
                    ps = this.connection.prepareStatement("DELETE FROM p2p_suggestion_purchase where user_id=?");
                    ps.setInt(1, userId);
                    int dRows = ps.executeUpdate();
                    log.info("deleted {} suggestion in purchase: {}", dRows, userId);
                }
                catch (SQLException sqle) {
                    sqle.printStackTrace();
                }
                catch (Exception e) {
                    e.printStackTrace();
                }
            }
            else{
                try {
                    ps = this.connection.prepareStatement("DELETE FROM p2p_suggestion_purchase where user_id=? and model=?");
                    ps.setInt(1, userId);
                    ps.setString(2,model);
                    int dRows = ps.executeUpdate();
                    log.info("deleted {} suggestion of model {} in purchase: {}", dRows, model, userId);
                }
                catch (SQLException sqle) {
                    sqle.printStackTrace();
                }
                catch (Exception e) {
                    e.printStackTrace();
                }
            }
        }
        // 刷新的时候全部清除推荐信息
        else if(todo.equals("F")) {
            try {
                ps = this.connection.prepareStatement("DELETE FROM p2p_suggestion_purchase where user_id=?");
                ps.setInt(1, userId);
                int dRows = ps.executeUpdate();
                log.info("deleted {} suggestion in purchase: {}", dRows, userId);
            }
            catch (SQLException sqle) {
                sqle.printStackTrace();
            }
            catch (Exception e) {
                e.printStackTrace();
            }
        }

        if(todo.equals("A")) {
            // 3.查找新的求购信息(当前牌号,根据需求调整大小)
            ArrayList purchaseList = new ArrayList();
            try {
                if(type>0){
                    ps = this.connection.prepareStatement("select id, model, `type` from p2p_purchase where model=? and user_id<>? and `type`=? order by id desc limit 10");
                    ps.setString(1, model);
                    ps.setInt(2, userId);
                    ps.setInt(3, type);
                }
                else{
                    ps = this.connection.prepareStatement("select id, model, `type` from p2p_purchase where model=? and user_id<>? order by id desc limit 10");
                    ps.setString(1, model);
                    ps.setInt(2, userId);
                }
                ResultSet rs = ps.executeQuery();
                while (rs.next()) {
                    int purchaseIdLine = rs.getInt(1);
                    String modelLine = rs.getString(2);
                    String typeLine = rs.getString(3);
                    purchaseList.add(Arrays.asList(purchaseIdLine, modelLine, typeLine));
                    log.info("add new model in purchaseList: {}[{}/{}/{}]", purchaseIdLine, userId, modelLine, typeLine);
                }
            } catch (SQLException e) {
                e.printStackTrace();
            }

            // 4.插入新的纪录
            for (int i = 0; i < purchaseList.size(); i++) {
                try {
                    ps = this.connection.prepareStatement("insert into p2p_suggestion_purchase (user_id, `type`, model, outter_id) VALUES (?, ?, ?, ?)");
                    List purchase = (List) purchaseList.get(i);
                    ps.setInt(1, userId);
                    String newType = "SELL";
                    if(purchase.get(2).equals("2")) {
                        newType = "BUY";
                    }
                    ps.setString(2, newType);
                    ps.setString(3, (String) purchase.get(1));
                    ps.setInt(4, (Integer) purchase.get(0));
                    int iRows = ps.executeUpdate();
                    log.info("add new suggestion in purchase: {}[{}/{}/{}]", (Integer) purchase.get(0), userId, (String) purchase.get(1), (String) purchase.get(2));
                } catch (SQLException sqle) {
                    sqle.printStackTrace();
                } catch (Exception e) {
                    e.printStackTrace();
                }
            }
        }

        if(todo.equals("F")) {
            // -.查找所有的关注牌号
            ArrayList modelList = new ArrayList();
            try {
                ps = this.connection.prepareStatement("select `name` from p2p_suggestion_model where user_id=? order by id desc limit 3");
                ps.setInt(1, userId);
                ResultSet rs = ps.executeQuery();
                while (rs.next()) {
                    String modelLine = rs.getString(1);
                    modelList.add(modelLine);
                    log.info("add new model in modelList: {}", modelLine);
                }
            } catch (SQLException e) {
                e.printStackTrace();
            }

            for(int m=0; m<modelList.size(); m++) {
                // 3.查找新的求购信息(当前牌号,根据需求调整大小)
                ArrayList purchaseList = new ArrayList();
                try {
                    ps = this.connection.prepareStatement("select id, model, `type` from p2p_purchase where model=? and sync in (6,7) and user_id<>? order by id desc limit 10");
                    ps.setString(1, (String) modelList.get(m));
                    ps.setInt(2, userId);
                    ResultSet rs = ps.executeQuery();
                    while (rs.next()) {
                        int purchaseIdLine = rs.getInt(1);
                        String modelLine = rs.getString(2);
                        String typeLine = rs.getString(3);
                        purchaseList.add(Arrays.asList(purchaseIdLine, modelLine, typeLine));
                        log.info("add new model in purchaseList: {}[{}/{}/{}]", purchaseIdLine, userId, modelLine, typeLine);
                    }
                } catch (SQLException e) {
                    e.printStackTrace();
                }

                // 4.插入新的纪录
                for (int i = 0; i < purchaseList.size(); i++) {
                    try {
                        ps = this.connection.prepareStatement("insert into p2p_suggestion_purchase (user_id, `type`, model, outter_id) VALUES (?, ?, ?, ?)");
                        List purchase = (List) purchaseList.get(i);
                        ps.setInt(1, userId);
                        ps.setString(2, (String) purchase.get(2));
                        ps.setString(3, (String) purchase.get(1));
                        ps.setInt(4, (Integer) purchase.get(0));
                        int iRows = ps.executeUpdate();
                        log.info("add new suggestion in purchase: {}[{}/{}/{}]", (Integer) purchase.get(0), userId, (String) purchase.get(1), (String) purchase.get(2));
                    } catch (SQLException sqle) {
                        sqle.printStackTrace();
                    } catch (Exception e) {
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

    /**
     * 定义输出字段
     * @param declarer
     */
    public void declareOutputFields(OutputFieldsDeclarer declarer) {
        declarer.declare(new Fields("word", "count", "ttl"));
    }
}

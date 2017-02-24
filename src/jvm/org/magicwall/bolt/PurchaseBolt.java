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
 * 这里根据销售和采购信息来更新推荐信息
 * 信息输入：user_id:purchase_id:do               purchase_id：购买或者销售的编号
 *                                                do可选：A/D
 */
public class PurchaseBolt extends BaseRichBolt {

    private OutputCollector collector;

    private static final Logger log = LoggerFactory.getLogger(PurchaseBolt.class);

    // MySQL
    final String url;
    final String driver;
    final String username;
    final String password;
    Connection connection;

    public PurchaseBolt(String url, String driver, String username, String password) {
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
        String product = input.getStringByField("purchasespout");
        log.info("received tuple : {}", product);
        String[] detail = product.split(":");
        int userId = Integer.parseInt(detail[0]);
        int productId = Integer.parseInt(detail[1]);
        // 从数据库里面查询
        // 1.发布的商品/求购信息(当前牌号)
        String sqlstr="select `type`, model from p2p_purchase where id=?";
        PreparedStatement ps= null;
        String type = "";
        String model = "";
        try {
            ps = this.connection.prepareStatement(sqlstr);
            ps.setInt(1,productId);
            ResultSet rs=ps.executeQuery();
            rs.next();
            type = rs.getInt(1)==0?"SALE":"BUY";   // 这里做了反转
            model = rs.getString(2);
            log.info("get model : {}", model);
        } catch (SQLException e) {
            e.printStackTrace();
        }

        // 2.删除旧的对应牌号的求购信息
        try {
            ps = this.connection.prepareStatement("DELETE FROM p2p_suggestion_purchase where `type`=? and model=?");
            ps.setString(1,type);
            ps.setString(2,model);
            int uRows = ps.executeUpdate();
            log.info("delete user's model:{}/{}", userId, model);
        }
        catch (SQLException sqle) {
            sqle.printStackTrace();
        }
        catch (Exception e) {
            e.printStackTrace();
        }

        // 3.查找新的求购信息(当前牌号,根据需求调整大小)
        ArrayList purchaseList = new ArrayList();
        try {
            ps = this.connection.prepareStatement("select id from p2p_purchase where `type`=? and sync in (6,7) and model=? and user_id<>? order by id desc limit 10");
            ps.setString(1, type);
            ps.setString(2, model);
            ps.setInt(3, userId);
            ResultSet rs=ps.executeQuery();
            while(rs.next()) {
                int purchaseId =rs.getInt(1);
                purchaseList.add(purchaseId);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }

        // 4.插入新的纪录
        for(int i=0; i<purchaseList.size(); i++) {
            try {
                ps = this.connection.prepareStatement("insert into p2p_suggestion_purchase (user_id, `type`, model, outter_id) VALUES (?, ?, ?, ?)");
                int purchaseId = (int) purchaseList.get(i);
                ps.setInt(1, userId);
                ps.setString(2, type);
                ps.setString(3, model);
                ps.setInt(4, purchaseId);
                int iRows = ps.executeUpdate();
                log.info("add new suggestion in purchase: {}[{}/{}/{}]", purchaseId, userId, model, type);
            }
            catch (SQLException sqle) {
                sqle.printStackTrace();
            }
            catch (Exception e) {
                e.printStackTrace();
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

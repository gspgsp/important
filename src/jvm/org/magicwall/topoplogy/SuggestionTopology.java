package org.magicwall.topoplogy;

import org.apache.storm.Config;
import org.apache.storm.generated.AlreadyAliveException;
import org.apache.storm.generated.AuthorizationException;
import org.apache.storm.generated.InvalidTopologyException;
import org.apache.storm.topology.TopologyBuilder;
import org.magicwall.bolt.PurchaseBolt;
import org.magicwall.bolt.FansBolt;
import org.magicwall.bolt.ModelBolt;
import org.magicwall.spout.PurchaseSpout;
import org.magicwall.spout.FansSpout;
import org.magicwall.spout.ModelSpout;
import org.magicwall.util.StormRunner;

import java.io.*;
import java.util.Properties;

public class SuggestionTopology {
    public static void main(String[] args) {
        String topoplogyName = args[0];
        Boolean isRemote = true;
        if(args.length>1) {
            if(!Boolean.parseBoolean(args[1])) {
                isRemote = false;
            }
        }

        // 获取Redis配置
        Properties redis = new Properties();
        InputStream inRedis = null;
        try {
            // 判断生产环境文件是否存在独立配置文件
            String redis_props = "/opt/suggestion/conf/redis.properties";
            File proFile=new File(redis_props);

            if(proFile.exists()) {
                try {
                    inRedis = new FileInputStream(proFile);
                } catch (FileNotFoundException e) {
                    e.printStackTrace();
                }
            }
            // 否则采用开发包里面的路径信息
            else{
                redis_props = "/resources/redis.properties";
                inRedis = SuggestionTopology.class.getResourceAsStream(redis_props);
            }
            redis.load(inRedis);
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
        String redisHost = redis.getProperty("redis.hostname");
        int redisPort = Integer.parseInt(redis.getProperty("redis.port"));

        // 获取MySQL配置
        Properties mysql = new Properties();
        InputStream inMySQL = null;
        try {
            // 判断生产环境文件是否存在独立配置文件
            String mysql_props = "/opt/suggestion/conf/mysql.properties";
            File proFile=new File(mysql_props);

            if(proFile.exists()) {
                try {
                    inMySQL = new FileInputStream(proFile);
                } catch (FileNotFoundException e) {
                    e.printStackTrace();
                }
            }
            // 否则采用开发包里面的路径信息
            else{
                mysql_props = "/resources/mysql.properties";
                inRedis = SuggestionTopology.class.getResourceAsStream(mysql_props);
            }
            mysql.load(inMySQL);
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
        String mysqlUrl = mysql.getProperty("jdbc.source.url");
        String mysqlDriver = mysql.getProperty("jdbc.source.driverClassName");
        String mysqlUser = mysql.getProperty("jdbc.source.username");
        String mysqlPasswd = mysql.getProperty("jdbc.source.passwd");

        // 2 - create and configure spout and bolt
        PurchaseSpout purchaseSpout = new PurchaseSpout(redisHost, redisPort);
        PurchaseBolt purchaseBolt = new PurchaseBolt(mysqlUrl, mysqlDriver, mysqlUser, mysqlPasswd);
        FansSpout fansSpout = new FansSpout(redisHost, redisPort);
        FansBolt fansBolt = new FansBolt(mysqlUrl, mysqlDriver, mysqlUser, mysqlPasswd);
        ModelSpout modelSpout = new ModelSpout(redisHost, redisPort);
        ModelBolt modelBolt = new ModelBolt(mysqlUrl, mysqlDriver, mysqlUser, mysqlPasswd);

        TopologyBuilder builder = new TopologyBuilder();
        builder.setSpout("purchasespout", purchaseSpout);
        builder.setBolt("purchasebolt", purchaseBolt).shuffleGrouping("purchasespout");
        builder.setSpout("fansspout", fansSpout);
        builder.setBolt("fansbolt", fansBolt).shuffleGrouping("fansspout");
        builder.setSpout("modelspout", modelSpout);
        builder.setBolt("modelbolt", modelBolt).shuffleGrouping("modelspout");

        Config conf = new Config();
        conf.setDebug(true);

        if(isRemote) {
            // 服务器运行
            try {
                StormRunner.runTopologyRemotely(builder.createTopology(), topoplogyName, conf);
            } catch (AlreadyAliveException e) {
                e.printStackTrace();
            } catch (InvalidTopologyException e) {
                e.printStackTrace();
            } catch (AuthorizationException e) {
                e.printStackTrace();
            }
        }
        else{
            // 本地运行
            conf.setDebug(true);
            try {
                StormRunner.runTopologyLocally(builder.createTopology(), topoplogyName, conf, 1);
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
        }
    }
}
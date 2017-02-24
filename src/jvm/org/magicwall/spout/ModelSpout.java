package org.magicwall.spout;

// Storm相关依赖
import org.apache.storm.spout.SpoutOutputCollector;
import org.apache.storm.task.TopologyContext;
import org.apache.storm.topology.OutputFieldsDeclarer;
import org.apache.storm.topology.base.BaseRichSpout;
import org.apache.storm.tuple.Fields;
import org.apache.storm.tuple.Values;
import org.apache.storm.utils.Utils;
import org.magicwall.bolt.PurchaseBolt;
import org.magicwall.util.RedisThread;

// Redis相关工具
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import redis.clients.jedis.JedisPool;
import redis.clients.jedis.JedisPoolConfig;

import java.util.*;
import java.util.concurrent.LinkedBlockingQueue;

/**
 * 源源不断的从Redis中订阅牌号信息
 */
public class ModelSpout extends BaseRichSpout {
    static final long serialVersionUID = 1L;

    SpoutOutputCollector collector;
    private static final Logger log = LoggerFactory.getLogger(PurchaseBolt.class);

    // Redis
    final String redisHost;
    final int redisPort;
    final String queue = "suggestion_model";
    LinkedBlockingQueue<String> tempQueue;   // 临时的本地队列
    JedisPool pool;

    public ModelSpout(String redisHost, int redisPort) {
        this.redisHost = redisHost;
        this.redisPort = redisPort;
    }

    public void open(Map conf, TopologyContext context, SpoutOutputCollector collector) {
        this.collector = collector;
        this.tempQueue = new LinkedBlockingQueue<String>(1000);
        // Redis连接池
        this.pool = new JedisPool(new JedisPoolConfig(), this.redisHost, this.redisPort);

        // 开启线程读取数据
        RedisThread listener = new RedisThread(this.tempQueue, this.pool, this.queue);
        listener.start();
    }

    public void close() {
        pool.destroy();
    }

    public void nextTuple() {
        String ret = tempQueue.poll();
        // 分割对应的字符串
        if (ret == null) {
            Utils.sleep(3000);
        } else {
            List<Object> values = new Values(ret);
            this.collector.emit(values);
        }
    }

    /**
     * 成功后的处理
     * @param msgId 成功的消息ID
     */
    public void ack(Object msgId) {
        // TODO Auto-generated method stub
        log.info("done model : {}", msgId);
    }

    /**
     * 失败后的处理
     * @param msgId 失败的消息ID
     */
    public void fail(Object msgId) {
        // TODO Auto-generated method stub
        log.info("error model : {}", msgId);
    }

    /**
     * 定义发送的消息字段
     * @param declarer
     */
    public void declareOutputFields(OutputFieldsDeclarer declarer) {
        declarer.declare(new Fields("modelspout"));
    }
}

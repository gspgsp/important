package org.magicwall.util;

import org.apache.storm.utils.Utils;
import redis.clients.jedis.Jedis;
import redis.clients.jedis.JedisPool;
import redis.clients.jedis.JedisPubSub;

import java.util.concurrent.LinkedBlockingQueue;

/**
 * redis队列数据
 */
public class RedisThread extends Thread {
    LinkedBlockingQueue<String> queue;
    JedisPool pool;
    String pattern;

    public RedisThread(LinkedBlockingQueue<String> queue, JedisPool pool, String pattern) {
        this.queue = queue;
        this.pool = pool;
        this.pattern = pattern;
    }

    public void run() {
        Jedis jedis = this.pool.getResource();
        try {
            while(true) {
                String tempStr = jedis.lpop(this.pattern);
                if(tempStr!=null) {
                    this.queue.add(tempStr);
                }
                else{
                    Utils.sleep(3000);
                }
            }
        } finally {
            this.pool.returnResourceObject(jedis);
        }
    }
};

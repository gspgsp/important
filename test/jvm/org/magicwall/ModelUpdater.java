package org.magicwall;

import org.apache.storm.task.TopologyContext;
import org.apache.storm.topology.BasicOutputCollector;
import org.apache.storm.topology.IBasicBolt;
import org.apache.storm.topology.OutputFieldsDeclarer;
import org.apache.storm.tuple.Fields;
import org.apache.storm.tuple.Tuple;
import org.apache.storm.tuple.Values;
import com.google.common.collect.Maps;

import java.util.Map;

/**
 * 这里是更新牌号
 */
public class ModelUpdater implements IBasicBolt {
    private Map<String, Integer> wordCounter = Maps.newHashMap();

    @SuppressWarnings("rawtypes")
    public void prepare(Map conf, TopologyContext context) {
    }

    /**
     * 执行部分
     * @param input         输入数据
     * @param collector     输出采集器
     */
    public void execute(Tuple input, BasicOutputCollector collector) {
        String word = input.getStringByField("message");
        int count;
        if (wordCounter.containsKey(word)) {
            count = wordCounter.get(word) + 1;
            wordCounter.put(word, wordCounter.get(word) + 1);
        } else {
            count = 1;
        }
        wordCounter.put(word, count);
        collector.emit(new Values(word, String.valueOf(count), 2));
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

    public Map<String, Object> getComponentConfiguration() {
        return null;
    }
}

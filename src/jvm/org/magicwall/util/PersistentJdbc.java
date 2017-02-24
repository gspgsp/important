package org.magicwall.util;

import org.apache.storm.jdbc.bolt.JdbcInsertBolt;
import org.apache.storm.jdbc.bolt.JdbcLookupBolt;
import org.apache.storm.jdbc.common.Column;
import org.apache.storm.jdbc.common.ConnectionProvider;
import org.apache.storm.jdbc.common.HikariCPConnectionProvider;
import org.apache.storm.jdbc.mapper.JdbcMapper;
import org.apache.storm.jdbc.mapper.SimpleJdbcLookupMapper;
import org.apache.storm.jdbc.mapper.SimpleJdbcMapper;
import org.apache.storm.tuple.Fields;

import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Properties;

/**
 * 持久化类
 */
public class PersistentJdbc {

    private static Map<String, Object> hikariConfigMap = new HashMap<String, Object>() {{
    }};
    public static ConnectionProvider connectionProvider = new HikariCPConnectionProvider(hikariConfigMap);

    /**
     * 构造函数，获取JDBC配置信息
     *
     * @throws IOException
     */
    public PersistentJdbc() {
        // 获取数据库配置
        Properties property = new Properties();
        InputStream in = null;
        try {
            in = new FileInputStream("config/mysql.properties");
            property.load(in);
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
        //words = new String(words.getBytes("ISO-8859-1"),"UTF-8");//转码
        hikariConfigMap.put("dataSourceClassName", property.getProperty("jdbc.source.driverClassName"));
        hikariConfigMap.put("dataSource.url", property.getProperty("jdbc.source.url"));
        hikariConfigMap.put("dataSource.user", property.getProperty("jdbc.source.username"));
        hikariConfigMap.put("dataSource.password", property.getProperty("jdbc.source.passwd"));
    }

    /**
     * 使用tablename进行插入数据，需要指定表中的所有字段
     * String tableName="userinfo";
     * JdbcMapper simpleJdbcMapper = new SimpleJdbcMapper(tableName, connectionProvider);
     * JdbcInsertBolt jdbcInsertBolt = new JdbcInsertBolt(connectionProvider, simpleJdbcMapper)
     * .withTableName("userinfo")
     * .withQueryTimeoutSecs(50);
     *
     * @return
     */
    public static JdbcInsertBolt getJdbcInsertTable(String tableName) {
        JdbcMapper simpleJdbcMapper = new SimpleJdbcMapper(tableName, connectionProvider);
        JdbcInsertBolt jdbcInsertBolt = new JdbcInsertBolt(connectionProvider, simpleJdbcMapper)
                .withTableName(tableName)
                .withQueryTimeoutSecs(50);
        return jdbcInsertBolt;
    }

    /**
     * 使用schemaColumns，可以指定字段要插入的字段
     *
     * @param schemaColumns 操作的列对象
     *                      List<Column> schemaColumns = Lists.newArrayList(
     *                      new Column("user_id", Types.VARCHAR),
     *                      new Column("resource_id", Types.VARCHAR),
     *                      new Column("count", Types.INTEGER)
     *                      );
     * @param sql           查询的SQL，支持占位符
     *                      例如："insert into userinfo(id,user_id,resource_id,count) values(?,?,?)"
     * @return
     */
    public static JdbcInsertBolt getJdbcInsertColumns(List<Column> schemaColumns, String sql) {
        JdbcMapper simpleJdbcMapper = new SimpleJdbcMapper(schemaColumns);
        JdbcInsertBolt jdbcInsertBolt = new JdbcInsertBolt(connectionProvider, simpleJdbcMapper)
                .withInsertQuery(sql)
                .withQueryTimeoutSecs(50);
        return jdbcInsertBolt;
    }

    /**
     * 查询功能
     *
     * @param outputFields 指定bolt的输出字段
     *                     例如：Fields outputFields = new Fields("user_id","resource_id","count");
     * @param queryColumns 指定查询条件字段
     *                     List<Column> queryColumns = Lists.newArrayList(
     *                     new Column("user_id", Types.VARCHAR),
     *                     new Column("resource_id",Types.VARCHAR)
     *                     );
     * @param sql          查询的语句
     *                     sql = "select count from userinfo where user_id=? and resource_id=?";
     * @return
     */
    public static JdbcLookupBolt getJdbcLookup(Fields outputFields, List<Column> queryColumns, String sql) {
        SimpleJdbcLookupMapper lookupMapper = new SimpleJdbcLookupMapper(outputFields, queryColumns);
        JdbcLookupBolt jdbcLookupBolt = new JdbcLookupBolt(connectionProvider, sql, lookupMapper);
        return jdbcLookupBolt;
    }
}
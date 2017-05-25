package org.magicwall;

import java.sql.*;//因使用了JDBC 类，所以需要引入此包：
public class Sample
{
    private static String url="jdbc:odbc:useDSN";
    private static Connection con;
    public static void main(String argv[])
    {
        try
        {   //加载驱动程序
            Class.forName("sun.jdbc.odbc.JdbcOdbcDriver");
            //建立连接
            con=DriverManager.getConnection(url,"sa","");


            //执行一般查询
            //创建Statement
            Statement usest=con.createStatement();
            //设置选项
            usest.setMaxRows(100);
            //执行查询
            ResultSet users=usest.executeQuery("select * from student");
            //获取选项设置情况
            System.out.println("ResultSet MaxRows:"+usest.getMaxRows());
            System.out.println("Query Time Out:"+usest.getMaxRows());

            //执行参数查询
            //创建PreparedStatement 对象
            String sqlstr="select * from student where age>?";
            PreparedStatement ps=con.prepareStatement(sqlstr);
            ps.setInt(1,15);
            ResultSet rs=ps.executeQuery();
            //获取结果集中的列名及其类型
            ResultSetMetaData rsmd=rs.getMetaData();
            int cc=rsmd.getColumnCount();
            System.out.print("ColumnName ColumnType");
            for(int i=1;i<=cc;i++)
            {
                System.out.print(rsmd.getColumnClassName(i)+" "+rsmd.getColumnTypeName(i));
            }

            //执行存储过程
            //创建CallableStatement 对象
            CallableStatement cs=con.prepareCall("{call testquery()}");
            rs = cs.executeQuery();
            //获取结果集中的列名及其类型
            rsmd = rs.getMetaData();
            cc = rsmd.getColumnCount();
            System.out.println("列名 列类型");
            for(int i=1;i<=cc;i++) {
                System.out.println(rsmd.getColumnName(i)+" "+rsmd.getColumnTypeName(i));
            }
        }
        catch(Exception e)
        {
            System.out.println(e.getMessage());
            e.printStackTrace();
        }
        finally {
            //关闭对象
            try {
                con.close();
            } catch (SQLException e) {
                e.printStackTrace();
            }
        }
    }
}
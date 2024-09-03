/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package datafiles;
import java.sql.*;
/**
 *
 * @author Aldrin-Du
 */
public class dbc {
    public static Connection connect(){
            try{
                Class.forName("com.mysql.cj.jdbc.Driver");
                Connection con = DriverManager.getConnection("jdbc:mysql://remotemysql.com/k6Fsv65jpL","k6Fsv65jpL","kWp5nzWpOj");
                return con;
            }
            catch(Exception e){
                e.printStackTrace();
            }
        return null;
    }
    /*public static void main(String[] args){
    Connection cont = connect();
    try{
    Statement st = cont.createStatement();
    ResultSet rs = st.executeQuery("select * from users where USERNAME='aldrin' and PASSWORD='owner'");
    if(rs.next()){
    System.out.print("connection : " + cont);
    }
    }catch(Exception e){e.printStackTrace();}
    }*/
}

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package datafiles;
import guis.*;
import java.sql.*;
import java.text.DateFormat;  
import java.text.SimpleDateFormat;  
import java.util.Date;  
import java.util.Calendar;  
/**
 *
 * @author Aldrin-Du
 */
public class loginfiles {
    public static void login(int admin,String us,boolean a){
        if(a==false){
            if(admin==1){
                mainFrame panel = new mainFrame();
                panel.setVisible(true);
            }
            else{
                employeePanel panel = new employeePanel();
                panel.setVisible(true);
            }
            
        }
        Connection con = dbc.connect();
            try{
                Statement st = con.createStatement();
                String username = us;
                Date date = Calendar.getInstance().getTime();  
                DateFormat dateFormat = new SimpleDateFormat("yyyy-mm-dd hh:mm:ss"); 
                String dateIn = dateFormat.format(date);
                st.executeUpdate("insert into log(USERNAME,DATE) values('"+username+"','"+dateIn+"')");
            }
            catch(Exception e){e.printStackTrace();}
     
    }
}

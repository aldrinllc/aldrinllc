package guis;

import java.awt.BorderLayout;
import net.proteanit.sql.DbUtils;
import java.awt.EventQueue;

import javax.swing.JFrame;
import javax.swing.JPanel;
import javax.swing.border.EmptyBorder;
import javax.swing.JButton;
import javax.swing.JLabel;
import javax.swing.JTextField;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import datafiles.dbc;
import java.awt.event.ActionListener;
import java.awt.event.ActionEvent;
public class returnBook extends JFrame {

	private JPanel contentPane;
	private JTextField name;
	private JTextField book;
	private JTable table;
	private JTable table_1;

	/**
	 * Launch the application.
	 */
	public static void main(String[] args) {
		EventQueue.invokeLater(new Runnable() {
			public void run() {
				try {
					returnBook frame = new returnBook();
					frame.setVisible(true);
				} catch (Exception e) {
					e.printStackTrace();
				}
			}
		});
	}

	/**
	 * Create the frame.
	 */
	public returnBook() {
		setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		setBounds(100, 100, 450, 300);
		contentPane = new JPanel();
		contentPane.setBorder(new EmptyBorder(5, 5, 5, 5));
		setContentPane(contentPane);
		contentPane.setLayout(null);
		
		JPanel panel = new JPanel();
		panel.setBounds(10, 11, 414, 239);
		contentPane.add(panel);
		panel.setLayout(null);
		
		JButton btnNewButton = new JButton("Confirm");
		btnNewButton.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent e) {
				String nm = name.getText();
				String bk = book.getText();
				java.sql.Connection con = dbc.connect();
				try(java.sql.Statement stmt = con.createStatement()){
					
					stmt.executeUpdate("delete from issue where Book='"+bk+"' and Name='"+nm+"'");
					
						javax.swing.JOptionPane.showMessageDialog(null, "Confirmed!");
						dispose();
				}catch(Exception q){q.printStackTrace();}
			}
		});
		btnNewButton.setBounds(315, 145, 89, 52);
		panel.add(btnNewButton);
		
		JLabel lblNewLabel = new JLabel("Borrower Name");
		lblNewLabel.setBounds(10, 145, 74, 14);
		panel.add(lblNewLabel);
		
		JLabel lblNewLabel_1 = new JLabel("Book Name");
		lblNewLabel_1.setBounds(20, 170, 60, 14);
		panel.add(lblNewLabel_1);
		
		name = new JTextField();
		name.setBounds(96, 142, 210, 20);
		panel.add(name);
		name.setColumns(10);
		
		book = new JTextField();
		book.setBounds(96, 170, 210, 20);
		panel.add(book);
		book.setColumns(10);
		
		JButton btnNewButton_1 = new JButton("Cancel");
		btnNewButton_1.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent e) {
				dispose();
			}
		});
		btnNewButton_1.setBounds(315, 208, 89, 23);
		panel.add(btnNewButton_1);
		
		JScrollPane scrollPane = new JScrollPane();
		scrollPane.setBounds(10, 11, 394, 124);
		panel.add(scrollPane);
		
		
		java.sql.Connection con = dbc.connect();
		try(java.sql.Statement stmt = con.createStatement()){
			java.sql.ResultSet rs = stmt.executeQuery("select * from issue");
			table = new JTable();
			table.setModel(DbUtils.resultSetToTableModel(rs));
			scrollPane.setViewportView(table);
			
		}catch(Exception q) {q.printStackTrace();}
	}
}

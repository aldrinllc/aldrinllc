package guis;

import java.awt.BorderLayout;
import net.proteanit.sql.DbUtils;
import java.awt.EventQueue;

import javax.swing.JFrame;
import javax.swing.JPanel;
import javax.swing.border.EmptyBorder;

import datafiles.dbc;

import javax.swing.JScrollPane;
import javax.swing.JButton;
import javax.swing.JTable;
import java.awt.event.ActionListener;
import java.awt.event.ActionEvent;

public class Log extends JFrame {

	private JPanel contentPane;
	private JTable table;

	/**
	 * Launch the application.
	 */
	public static void main(String[] args) {
		EventQueue.invokeLater(new Runnable() {
			public void run() {
				try {
					Log frame = new Log();
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
	public Log() {
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
		
		JScrollPane scrollPane = new JScrollPane();
		scrollPane.setBounds(10, 11, 394, 180);
		panel.add(scrollPane);
		java.sql.Connection con = dbc.connect();
		
		try (java.sql.Statement stmt = con.createStatement()){
			java.sql.ResultSet rs = stmt.executeQuery("select * from log");
			table = new JTable();
			table.setModel(DbUtils.resultSetToTableModel(rs));
			scrollPane.setViewportView(table);
		}catch(Exception d) {
			d.printStackTrace();
		}
		
		JButton btnNewButton = new JButton("Close");
		btnNewButton.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent e) {
			dispose();
			}
		});
		btnNewButton.setBounds(315, 202, 89, 23);
		panel.add(btnNewButton);
	}
}

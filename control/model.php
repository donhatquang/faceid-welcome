<?php
	//liet ke cacs ban ghi
	class model{
		public function fetch($sql){
			//khai bao bien ket noi, la bien toan cuc
			global $con;
			$result = mysqli_query($con,$sql);
			if (!$result) {
			    printf("Error: %s\n", mysqli_error($con));
			    exit();
			}
			//duyet cac ban ghi
			$arr = array();
			while($rows = mysqli_fetch_array($result))
				$arr[] = $rows;
			return $arr;
		}
		//duyet mot ban ghi
		public function fetch_one($sql){
			global $con;
			$result = mysqli_query($con,$sql);
			if (!$result) {
			    printf("Error: %s\n", mysqli_error($con));
			    exit();
			}
			//duyet ban ghi dau tien, tra ket qua ve bien rows
			$rows = mysqli_fetch_array($result);
			return $rows;
		}
		public function execute($sql){
			global $con;
			mysqli_query($con,$sql);
		}
		public function count($sql){
			global $con;
			$result = mysqli_query($con,$sql);
			//tra ve tong so ban ghi
			return mysqli_num_rows($result);
		}
		public function get_insert_id($id_name,$tbl_name){
			global $con;
			$result = mysqli_query($con,"select $id_name from $tbl_name order by $id_name desc limit 0,1");
			$rows = mysqli_fetch_array($reulst);
			return $rows;
		}
	}
?>
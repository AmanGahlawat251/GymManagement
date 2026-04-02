<?php
    global $config;
    use Dompdf\Dompdf;
     class MySqliDriver implements DB
	 {

		var $sql;
		var $rs;
		var $numrows;
		var $limit;
        var $noofpage;
		var $offset;
		var $page;
		var $style;
		var $parameter;
		var $activestyle;
		var $buttonstyle;
		private $host;
		private $user;
		private $pass;
		private $database;
		private $cnx;
		

	    function __construct()
            {
			//register_shutdown_function(array(&$this, 'ShutDown')); 
          
				$this->host =  HOST;
				$this->user = USER;
				$this->pass = PASSWORD;
				$this->database = DATABASE;
				
				$this->cnx = mysqli_connect($this->host,$this->user,$this->pass,$this->database)or die("Cannot connect to MySQL" . mysqli_error());
				if(mysqli_connect_error())
				{
					trigger_error('Database connection failed: '. mysqli_connect_error(),E_USER_ERROR);
				}				
            }

		 
function singleValue_new($tbl,$id,$con) {
			
                $sql="select ".$id." from ".$tbl." where ".$con;
				$result=mysqli_query($this->cnx,$sql) or die(mysqli_error($this->cnx));
                $rows=mysqli_num_rows($result);
                if($rows>0)
                {
					$rec = mysqli_fetch_array($result);
                    return $rec[0];
                }
                else
                {
                   return $rec[0];
                } 
        }
function get_group_concat_val($tbl,$field,$con) 
	{
		$rec = array();
		$sql = "SELECT GROUP_CONCAT(".$field." SEPARATOR  ',') as ".$field." FROM ".$tbl." WHERE ".$con;
		$result = mysqli_query($this->cnx,$sql) or die(mysqli_error($this->cnx));
		if($result)
		{
			$rows = $this->num_rows($result);
			if($rows>0)
			{
				$rec = $this->fetch_object($result);				
				return $rec->$field;
			}
			else
			{
			   return $rec->$field;
			} 
		}
	}
		
		
		function selectQry($table,$condition,$limitS,$limitE)
		{
                    $dataSet = array();
                    if(!$condition){
				if((!$limitS) && (!$limitE))
					$sql="select * from ".$table;
				else
					$sql="select * from ".$table." limit ".$limitS.", ".$limitE;
			}
			else {
				if((!$limitS) && (!$limitE))
					$sql="select * from ".$table." where  ".$condition;
				else
					$sql="select * from ".$table." where  ".$condition." limit ".$limitS.", ".$limitE;
			}
                       // echo  $sql;
			$dataSet=$this->executeQry($sql);
                       // print_r($dataSet);
			return $dataSet;
		}
		

		function executeQry(string $sql)
		{
			if (!is_string($sql)) {
	            //throw new Exception("Illegal parameter. Must be string.");
				die("Illegal parameter. Must be string.");
	        }
			else {
			$rsSet = mysqli_query($this->cnx,$sql);
			}

            return $rsSet;
		}
	/* 	public function executeQry(string $sql) {
        if (!is_string($sql)) {
            //throw new Exception("Illegal parameter. Must be string.");
            die("Illegal parameter. Must be string.");
        } else {
            // Execute the query
            $rsSet = mysqli_query($this->cnx, $sql);

            // Check if the query was successful
            if ($rsSet !== false) {
                // Update the affected rows count property
                $this->affected_rows_count = mysqli_affected_rows($this->cnx);
            }
        }

        return $rsSet;
    }

    public function affectedRows() {
        // Return the affected rows count
        return $this->affected_rows_count;
    } */
		function insert_id()  
		{
			return mysqli_insert_id($this->cnx);
		}

		
		

		 

		function getTotalRow($DataSet) { 
			return mysqli_num_rows($DataSet);
		}

		 

	    function fetchValue(string $tbl, string $field, string $con) 
	    {
                $response = (object) array();
                $sql="select ".$field." from ".$tbl." where ".$con;
	        $result = mysqli_query($this->cnx,$sql) or die(mysqli_error($this->cnx));
                $rows=mysqli_num_rows($result);
                if($rows>0)
                {
                   $rec = mysqli_fetch_object($result);
                   $response = $rec->$field ;

                }

			return $response;
	    }
            
function DynamicSelectedDropDown(string $query, string $id_field, string  $name_field, $selected_id="")
	{
		//echo $query;
		$result = $this->executeQry($query);
		while($row = $this->fetch_array($result))
		{
			$a = trim($row[$id_field]);
			echo '<option value="'.trim($row[$id_field]).'"';
			if(is_array($selected_id))
			{			
				foreach($selected_id as $id_select)
				if($a==$id_select)
				{
					echo 'selected';
				}
			}
			else
			{
				
				if($a==$selected_id)
				{
					echo 'selected';
				}
			}
			echo '>'. ucwords(trim($row[$name_field])) . '</option>'."\n";
		}    
	}
	
	function DynamicMultiLabalDropDown(string $query, string $id_field, string  $name_field, string $name_field2 )
	{
		//echo $query;
		$result = $this->executeQry($query);
		while($row = $this->fetch_array($result))
		{
			$a = trim($row[$id_field]);
			echo '<option value="'.trim($row[$id_field]).'"';
			 
			echo '>'. trim($row[$name_field]) .' ('. trim($row[$name_field2]).') '. '</option>'."\n";
		}    
	}


function DynamicDropDown(string $tbl,string $id_field,string $name_field,string $name_field2) 
{
    if(empty($name_field2) || $name_field2 == '')
        $Query = "select $id_field, $name_field from  ".$tbl." ORDER BY $name_field ASC" ;
    else
        $Query = "select $id_field, $name_field, $name_field2 from  ".$tbl." ORDER BY $name_field ASC" ;
    $result = $this->executeQry($Query);

    while($row = $this->fetch_array($result))
    {
        if(empty($name_field2) || $name_field2 == '')
            echo '<option value="'.trim($row[0]).'">'. trim($row[1]). '</option>';
        else
            echo '<option value="'.trim($row[0]).'">'. trim($row[1]) .' '.trim($row[2]). '</option>';
    }
}

function DynamicDropDownOrderby(string $tbl,string $id_field, string $name_field, string $name_field2, string $orderby)
{
    if(empty($name_field2) || $name_field2 == '')
        $Query = "select $id_field, $name_field from  ".$tbl." ORDER BY $orderby ASC" ;
    else
        $Query = "select $id_field, $name_field, $name_field2 from  ".$tbl." ORDER BY $orderby ASC" ;
    $result = $this->executeQry($Query);
	
    while($row = $this->fetch_array($result))
    {
        if(empty($name_field2) || $name_field2 == '')
            echo '<option value="'.trim($row[0]).'">'. trim($row[1]). '</option>';
        else
            echo '<option value="'.trim($row[0]).'">'. trim($row[1]) .' '.trim($row[2]). '</option>';
    }
}

 function GetNameByID(string $tbl, string $id_field, string $name_field, $id) 
                {
                  $query = "select ". $name_field. " from "  .$tbl.  " where $id_field = '$id'";
                   $result = $this->executeQry($query);
                   $rows = $this->getTotalRow($result);
                    $Name = "";
                    if($rows>0)
                    {
                        while($row = $this->fetch_array($result))
                        {
                            $Name =  trim($row[$name_field]);
                        }
                    }
                    return $Name ;
                }

  function ShowDetails($tbl,$id,$id_field)
                {
                    $query = "select * from " .$tbl.  "  where $id_field = $id ";
                    $result = $this->executeQry($query) ;
                    //  echo "<script>alert('$query')</script>";
                    if(!isset($result))
                    {
                        echo "Sorry, This is an Invalid Account Id";
                    }
                    else
                    {
                        $row = $this->fetch_array($result);
                        return $row;
                    }
                }
            function fetchRows($tbl, $field, $con)
	    {
                $response = "";
                $sql="select ".$field." from ".$tbl." where ".$con;
	        $result = mysqli_query($this->cnx,$sql) or die(mysqli_error($this->cnx));
                $rows=mysqli_num_rows($result);
                return $rows;
	    }

	    
	     
		
		function singleRowObject($tbl,$con)
	    {
		$rec = array();
              $sql="select * from ".$tbl." where ".$con;
	        $result=mysqli_query($this->cnx,$sql) or die(mysqli_error($this->cnx));
                 $rows=mysqli_num_rows($result);
                if($rows>0)
                {
			$rec=mysqli_fetch_object($result);
                        return $rec;
                }
                else
                {
                   // $rec[0] = "";
                    return $rec;
                }
	    }
		
		 
		
		function singleRowAssoc_new($select,$tbl,$con)
	    {
		$rec = array();
               $sql="select ".$select." from ".$tbl." where ".$con;
	        $result=mysqli_query($this->cnx,$sql) or die(mysqli_error($this->cnx));
                 $rows=mysqli_num_rows($result);
                if($rows>0)
                {
			$rec=mysqli_fetch_assoc($result); 
                        return $rec;
                }
                else
                {
                   // $rec[0] = "";
                    return $rec;
                }
	    }

            function check_var($var)
            {
                if(isset($var))
                    echo $var;
            }
	    function deleteRec(string $table, string $condition)  
	    {

			if(!$condition){
				$sql="delete from ".$table;
			}
			else{
				$sql="delete from ".$table." where ".$condition;
			}
	    //echo $sql;
	    //exit;
	    $dataSet=$this->executeQry($sql);
			return $dataSet;
	    }


		///// Alok Changes

	function updateTable($table, $pk, $pkval,$post)
	{
	    $query = "UPDATE ".$table." SET ";
	    $trigger = 0;
	    foreach($post as $field => $value){
			if(substr($field,0,3) == "db_"){
				$field = str_replace("db_","",$field);
				$field." = ".$value."<br><br>";
			    $value = mysqli_real_escape_string($value);
				if($trigger > 0) $query = $query . ", ";
			    $query = $query . $field." = trim('$value')";
			    $trigger++;
	    	}
		}
	    $query = $query . " WHERE ".$pk." = '$pkval'";
	   	// echo $query."<br><br>";exit;
	    if($result = mysqli_query($query)) return(0);
	    else echo(mysqli_error());
	}

        function newmsg ($id)
        {
           echo $response =  '<div style="display:none" id="newmsgdiv" class="bubble me">
                        <div class="media"><small class="text-muted">'.$_SESSION["name"].' | Just Now</small>
                       <span id="del^'.$id.'" onclick="editchat(this.id)" style="margin-left:05px; cursor:pointer" class="label label-danger label-sm pull-right"><i class="fa fa-trash-o fa-1x"></i></span>
                   <span id="edit^'.$id.'" onclick="editchat(this.id);" style="cursor:pointer" class="label label-info label-sm pull-right"><i class="fa fa-edit fa-1x"></i></span>
                       <div style="word-wrap:break-word;" class="media-body" ><p style="width:90%;"  id="txtdiv'.$id.'" class="text">
                                </p>
                            </div>
                        </div>
                    </div>';
        }

        function singleValue(string $tbl, string $col, string $con) {
			
				$sql="select ".$col." from ".$tbl." where ".$con;
				$result=mysqli_query($this->cnx,$sql) or die(mysqli_error($this->cnx));
                $rows=mysqli_num_rows($result);
				
                if($rows>0)
                {
					$rec=mysqli_fetch_object($result);
					$col = $rec->$col;
					return $col;
                }
                else
                {
                    $col = "";
                    return $col;
                }
        }

       function getMySqlColumns(string $table)  
	{
	  	$query = "SELECT * FROM {$table} LIMIT 1";
	  	$result = mysqli_query($query);
	  	$row = mysqli_fetch_assoc($result);
	  	$columns = array_keys($row);
	  	return $columns;
	}

 	function mysqli_column_names(string $table)   {
 		return $this->getMySqlColumns($table);
	}

	// Paging Test

	function paging(string $query){
		$this->offset=0;
		$this->page=1;
		$this->sql=$query;
		$this->rs=mysqli_query($this->sql);
		$this->numrows=mysqli_num_rows($this->rs);
	}
	function getNumRows() : int {
		return $this->numrows;
	}
	function setLimit(int $no) {
        if($no)
		$this->limit=$no;
		else
		$this->limit=10;
	}
	function getLimit() {
		return $this->limit;
	}
	function getNoOfPages() {
		return ceil($this->noofpage=($this->getNumRows()/$this->getLimit()));
	}
	function getPageNo() {
		$str="";
		$str=$str."<table width='100%' border='0' align='right'><tr>";
		$str=$str."<td width='100%' align='right' valign='top' height='25'>";
		if($this->getPage()>1) {
			$str=$str."<a href='".$_SERVER['PHP_SELF']."?page=".($this->getPage()-1).$this->getParameter()."' class='".$this->getStyle()."'>Prev </a>|&nbsp;";
		}
		if($this->getPage() > 6)
		{
		    $l = 1;
			for($i=$this->getPage()-1;$i>0;$i--) {
				$arr[] = "<a href='".$_SERVER['PHP_SELF']."?page=".$i.$this->getParameter()."' class='".$this->getStyle()."'>".$i."</a>&nbsp;";
				if($l == 5)
				break;
				$l++;
			}
			if($this->getNoOfPages()-$this->getPage() < 5)
			{
			   $start = $i -1;
			   $diff = $this->getNoOfPages()-$this->getPage();
			   $loop = 5-$diff;
			   for($m = 1; $m<=$loop; $m++) {
			     if($start>0)
			     $arr[] = "<a href='".$_SERVER['PHP_SELF']."?page=".$start.$this->getParameter()."' class='".$this->getStyle()."'>".$start."</a>&nbsp;";
			     $start--;
				}
			}
			$arrrev = array_reverse($arr);
			foreach($arrrev as $val)
			  $str = $str.$val;
		}

		$current = $this->getPage();
		if($current > 6)
		{
		    $k = 1;
			for($i=$current;$i<=$this->getNoOfPages();$i++) {
				if($i==$this->getPage()) {
					$str=$str."<span class='".$this->getActiveStyle()."'>".$i."&nbsp;</span>";
				}
				else {
					$str=$str."<a href='".$_SERVER['PHP_SELF']."?page=".$i.$this->getParameter()."' class='".$this->getStyle()."'>".$i."</a>&nbsp;";
				}
				if($k == 6)
				break;
				$k++;
			}
		}
		else
		{
			$j = 1;
			for($i=1;$i<=$this->getNoOfPages();$i++) {
				if($i==$this->getPage()) {
					$str=$str."<span class='".$this->getActiveStyle()."'>".$i."&nbsp;</span>";
				}
				else {
					$str=$str."<a href='".$_SERVER['PHP_SELF']."?page=".$i.$this->getParameter()."' class='".$this->getStyle()."'>".$i."</a>&nbsp;";
				}
			   if($j == 11)
			   break;
			   $j++;
			}
		  	if($this->getNoOfPages() > $i+1)
		  	{
		   	 	$str=$str."<a href='".$_SERVER['PHP_SELF']."?page=".($i+1).$this->getParameter()."' class='".$this->getStyle()."'>.. </a>";
		  	}
		}

		if($this->getPage()<$this->getNoOfPages()) {
			$str=$str."|<a href='".$_SERVER['PHP_SELF']."?page=".($this->getPage()+1).$this->getParameter()."' class='".$this->getStyle()."'> Next</a>";
		}
		$str=$str."</td>";
		$str=$str."</tr></table>";
		return $str;
	}

	function getOffset($page) {
		if($page>$this->getNoOfPages()) {
			$page=$this->getNoOfPages();
		}
		if($page=="") {
			$this->page=1;
			$page=1;
		}
		else {
			$this->page=$page;
		}
		if($page=="1") {
			$this->offset=0;
			return $this->offset;
		}
		else {
			for($i=2;$i<=$page;$i++) {
				$this->offset=$this->offset+$this->getLimit();
			}
			return $this->offset;
		}
	}
	function getPage() {
		return $this->page;
	}
	function setStyle($style) {
		$this->style=$style;
	}
	function getStyle() {
		return $this->style;
	}
	function setActiveStyle($style) {
		$this->activestyle=$style;
	}
	function getActiveStyle() {
		return $this->activestyle;
	}
	function setButtonStyle($style) {
		$this->buttonstyle=$style;
	}
	function getButtonStyle() {
		return $this->buttonstyle;
	}
	function setParameter($parameter) {
		$this->parameter=$parameter;
	}
	function getParameter() {
		return $this->parameter;
	}


	function getQueryString()
	{
	  	$queryString_arr = explode('&',$_SERVER['QUERY_STRING']);
	  	$queryStringNew = "";
	  	if(count($queryString_arr) > 0)
	  	{
	   		$srchString = "age=";
	   		$srchStringLimit = "imit=";
	   		foreach($queryString_arr as $queryString_arr2)
	   		{
	    		$posString = strpos($queryString_arr2,$srchString);
	    		$posStringLim = strpos($queryString_arr2,$srchStringLimit);
	    		if($posString != 1 && $queryString_arr2 != '')
	    		$queryStringNew .= "&".$queryString_arr2;
	   		}
	   		//echo  $queryStringNew; exit;
	  	}
	  	/*if($_GET['limit'] != '') {
	    	$queryStringNew .= "&limit=".$_GET['limit'];
	  	}*/
	  	return $queryStringNew;
	}

	public function errno()
	{
		return mysqli_errno($this->cnx);
	}
	public function error()
	{
		return mysqli_error($this->cnx);
	}
	public function escape($string)
	{
		return mysqli_real_escape_string($this->cnx,$string);

	}
	
	public function fetch_array($result, $array_type = MYSQLI_BOTH)
	{
		return mysqli_fetch_array($result, $array_type);
	}
	public function fetch_row($result)
	{
	   return mysqli_fetch_row($result);
	}
	public function fetch_assoc($result)
	{
	   return mysqli_fetch_assoc($result);
	}
	public function fetch_object($result)
	{
	   return mysqli_fetch_object($result);
	}
	public function num_rows($result)
	{
	   return mysqli_num_rows($result);
	}
	public function curPageInfo() {
    return substr($_SERVER["REQUEST_URI"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	}


	 public  function safe_b64encode($string) {
    $data = base64_encode($string);
    $data = str_replace(array('+','/','='),array('-','_',''),$data);
    return $data;
    }

    public function safe_b64decode($string) {
    $data = str_replace(array('-','_'),array('+','/'),$string);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
    $data .= substr('====', $mod4);
    }
    return base64_decode($data);
    }
	
	 
	
	public function encode($string)
    {
		$options = 0; 
		$ciphering = "AES-256-OFB"; 
		$encryption_iv = '7970970978812341'; 
		$encryption_key = "@dministratorq1w2e3r4@sagacious";
		
        if (function_exists('mcrypt_encrypt'))
            $val = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, $string, MCRYPT_MODE_ECB);
        else
            $val = openssl_encrypt($string, $ciphering, $encryption_key, $options, $encryption_iv);

        return $val;
    }

	public function decode($string)
    {
		$decryption_iv = '7970970978812341'; 
		$decryption_key = "@dministratorq1w2e3r4@sagacious"; 
		$ciphering = "AES-256-OFB";
		$options = 0; 

        if (function_exists('mcrypt_encrypt'))
            $val = mcrypt_decrypt(MCRYPT_BLOWFISH, $decryption_key, $string, MCRYPT_MODE_ECB);
        else
           $val = openssl_decrypt($string, $ciphering, $decryption_key, $options, $decryption_iv); 
		
        return $val;
    }
	
    public function formatdate ($RecTimeStamp,$format)
    {
         //$format = "j-M-Y h:i:A";
         if(!empty($RecTimeStamp) || $RecTimeStamp != "")
         $RecTimeStamp = Date($format, strtotime(str_replace("/", "-", $RecTimeStamp)));

         return $RecTimeStamp;

    }

    function geturlvalue($decrypt_val,$value)
{

	$arrV = explode('&',$decrypt_val);
	foreach($arrV as $val)
	{
		$arrI = explode('=',$val);
		if($arrI[0]==$value)
		{
			$finalValue = $arrI[1];
			return $finalValue;
		}
	 }
}

 
 
function geturlvalueMod($arr)
{
	$ar = array();
	if (is_array($arr))
	{
		foreach($arr as $value)
		{
			$val = explode('=',$value);
			if($val[0] == 'stat')
				$ar[$val[0]] = $this->encode($val[1]);
			else
				$ar[$val[0]] = $val[1];
				
		}
	}
	else
	{
		$val = explode('=',$arr);
		$ar[$val[0]] = $this->encode($val[1]);
	}
	
	return $ar;
}
function option($name)
		
	{
		//echo $this->encode('stat=listuser');
		//$name = $_SERVER['REQUEST_URI'];
		$url = explode("?", $name);
		$urlvalueMod = array();
			if (count($url) > 0)
			{
				if (isset($url['1']))
				{
					// Primary expected format: index.php?{ENCODED("stat=...&k=v")}
					// Backward/loose format support: index.php?{ENCODED("stat=...")}&k=v
					$rawQuery = $url['1'];
					$decodedQuery = $this->decode($rawQuery);

					// If full decode doesn't look like a query string, try decoding only the first token
					// before '&' and then append the raw remainder (which is already plain).
					if (empty($decodedQuery) || strpos($decodedQuery, 'stat=') === false) {
						$rawParts = explode('&', $rawQuery, 2);
						$decodedFirst = $this->decode($rawParts[0]);
						if (!empty($decodedFirst) && strpos($decodedFirst, 'stat=') !== false) {
							$decodedQuery = $decodedFirst . (isset($rawParts[1]) ? '&' . $rawParts[1] : '');
						}
					}

					$spliturl = explode("&", (string)$decodedQuery);
					//print_r($spliturl); die;
				}
				else
					$spliturl = array();
			
				if (is_array($spliturl))
				{
					if (isset($spliturl['0']))
						$decrypt_val = $spliturl['0'];
				}
				else
				{
					if (isset($url['1']))
						$decrypt_val = $url['1'];
				}
				if (isset($decrypt_val))
				{
					$urlvalueMod = $this->geturlvalueMod($spliturl);
				}
			}
		 return $urlvalueMod;
	}
	
	function catchError($errno = '', $errstr = '', $errfile = '', $errline = '', $appname = '') {
		$strerror = "";
        $strerror .="Eroor Type : " . $errno . "<br>";
        $strerror .= "Eroor Message : " . $errstr . "<br>";
        $strerror .= "Line Number : " . $errline . "<br>";
		$strerror .= "File Name : " . $errfile;
        $subject = "Error Reporting from ".$appname;
        echo $body = "Error:\n" . $strerror;
        $fromName = $appname;
        $attachmentPath = '';
		//$this->sendEmailNotifocation($toEmail, $subject, $body, $attachmentPath = '', $fromEmail = '', $fromName = '', $toName = '');
		// sendEmailNotifocation() is commented out in this project.
		// Use sendEmails() instead to avoid secondary fatal errors.
		$this->sendEmails($subject, $body, '', EXCEPTION_EMAIL, APP_FULL_NAME , DEVELOPER_EMAIL, 'Chandan Singh','');
        exit();
    }
	
	function ShutDown() 
	{
        $lasterror = error_get_last();
        #print_r($lasterror);
		if(isset($lasterror['type']))
		{	
                    //E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR, E_CORE_WARNING, E_COMPILE_WARNING, E_PARSE
			if (in_array($lasterror['type'], Array(E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR, E_COMPILE_WARNING, E_PARSE)))
			{
				$this->catchError($lasterror['type'], $lasterror['message'], $lasterror['file'], $lasterror['line'], APP_NAME);
			}
		}
    }
	
	function addpadding($string, $blocksize = 32)
		{
				$len = strlen($string);
				$pad = $blocksize - ($len % $blocksize);
				$string .= str_repeat(chr($pad), $pad);
				return $string;
		}
	
	function encrypt($string = "")
		{
			$key = base64_decode("PSVJQRk9QTEpNVU1DWUZCRVFGV1VVT0ZOV1RRU1NaWR=");
			$iv = base64_decode("YWlFLVEZZUFNaWlhPQ01ZT0lLWU5HTFJQVFNCRUJZVA=");
			return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $this->addpadding($string), MCRYPT_MODE_CBC, $iv));
		}

// PHP:Decrypt Code:
	function strippadding($string)
		{
			$slast = ord(substr($string, -1));
			$slastc = chr($slast);
			$pcheck = substr($string, -$slast);
			if(preg_match("/$slastc{".$slast."}/", $string)){
			$string = substr($string, 0, strlen($string)-$slast);
				return $string;
			} else {
				return false;
			}
	   }
	function decrypt($string)
		{
			$key = base64_decode("PSVJQRk9QTEpNVU1DWUZCRVFGV1VVT0ZOV1RRU1NaWR=");
			$iv = base64_decode("YWlFLVEZZUFNaWlhPQ01ZT0lLWU5HTFJQVFNCRUJZVA=");
			$string = base64_decode($string);
			return $this->strippadding(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_CBC, $iv));
		}
	
	
	
	function distinct_dropdown($col,$tbl,$con,$select)
	{
			
		echo $sql = " select distinct($col) from $tbl $con order by $col ASC";
						$result = $this->executeQry($sql);
						while($row = $this->fetch_array($result))
						{
							if($row[$col] == '' || strpos($row[$col], "?") !== false || $row[$col] == '1')
								continue;
							else
							{
							echo '<option value="'.trim($row[$col]).'"';
							if($row[$col] == $select)
								echo 'selected';							
							}
						
							echo '>'.strtoupper(($row[$col])). '</option>'."\n";
						}
						
       
	}
	
	
	
	function qry($arr, $tbl)
	{
		$count = 0;
		$fields = '';
		foreach($arr as $col => $val) 
		{
			if ($count++ != 0) $fields .= ', ';
			if(is_array($val))
				{
					$val = implode(',',$val);
				}
			$fields .= "`$col` = '".$val."'";
		}
		$query = "INSERT INTO ".$tbl." SET $fields;";
		return $query;
	}
	
	function update_qry($arr,$tbl,$condition)
	{
		$count = 0;
		$fields = '';
		foreach($arr as $col => $val) 
		{
			if ($count++ != 0) $fields .= ', ';
			if(is_array($val))
				{
					$val = implode(',',$val);
					
				}
			$fields .= "`$col` = '".$val."'";
		}
		$query = "update ".$tbl." SET $fields $condition";
		return $query;
	}
	 
	function generateRandomString($length,$type="")
	{
		if($type=="SMALL_STR")			
			$characters = '0123456789abcdefghijklmnopqrstuvwxyz';		
		else if($type=="CAPITAL_STR")			
			$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		else if($type=="SMALL_STR_WITHOUT_NUM")			
			$characters = 'abcdefghijklmnopqrstuvwxyz';		
		else if($type=="CAPITAL_STR_WITHOUT_NUM")			
			$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		else
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	
	 
	
	function send_message($mobile,$messagecontent)
	{
		$username = urlencode(SMS_UNAME);
		$password = urlencode(SMS_PASSWORD);
		$sender   = urlencode(SMS_SENDERID);		
		$message  = urlencode($messagecontent);
		$customer_id = $_SESSION['User_Id'];
		$account_id = $_SESSION['account_id'];
		$RecTimeStamp = Date("Y/m/d H:m:s");
		$url = SMS_URL."uname=$username&pwd=$password&senderid=$sender&to=$mobile&msg=$message&route=T";		
		$request = curl_init();
		$timeOut = 0;
		curl_setopt ($request, CURLOPT_URL, $url);
		curl_setopt ($request, CURLOPT_RETURNTRANSFER, 1);		
		curl_setopt ($request, CURLOPT_CONNECTTIMEOUT, $timeOut);
		$response = curl_exec($request);
		curl_close($request); 
		$response = trim($response);
		$sql = "INSERT INTO ".MESSAGE." (mob_no, account_id, customer_id, message, response_code, request_url, RecTimeStamp) VALUES ('$mobile', '$account_id', '$customer_id', '$messagecontent', '$response', '$url', '$RecTimeStamp')";
		$result = $this->executeQry($sql);  
		return $response;
	}
	

	function create_directory($folder_path)
	{
		if(!is_dir ($folder_path))
		{
			if (mkdir($folder_path, 0, true))
			{
			   chmod($folder_path, 0777);
			   return 1;
			}
			else
			{
				$msg = "Unable to create folder.";
				return $msg;
			}
		}
		else
		{
			return 1;
		}
	}
	
	function Resquest_Response_log($logid, $request_type = '', $response_array = '', $requestStr = '', $record_id = '', $log = array()) 
	{
		$table = 'tbl_request_response_log';
		$record = '';
		$log_text = '';	
		if($record_id != '')
		{
			$record = " , record_id = '" . addslashes($record_id) . "'";
		}
		
		if(count($log) > 0)
		{
			$log_text = " , log = '".addslashes(json_encode($log))."'";
		}
		if ($this->num_rows($this->executeQry("SHOW TABLES LIKE '" . $table . "'")) == 1) 
		{
			if ($logid > 0) 
			{
				if (is_array($response_array)) 
				{
					$response = json_encode($response_array);
				} 
				else 
				{
					$response = $response_array;
				}
				$sql = "update " . $table . " set response='" . addslashes($response) . "' , date_time_response = NOW(3), timetaken = (TIMESTAMPDIFF(MICROSECOND, date_time_request, now(3))/1000) ".$record.$log_text. " where id='$logid'";

				$this->executeQry($sql);
			} 
			else 
			{
				$user_name = '';
				$ip = $this->get_user_ip();
				if(isset($_SESSION['login_id']))
				{
					$user_name = $_SESSION['login_id'];
				}
				
				 $sql = "insert into " . $table . " set response='" . addslashes($response_array) . "' , request_type='" . addslashes($request_type) . "', request='" . addslashes($requestStr) . "',  date_time_request = NOW(3), user_name = '".$user_name."', ip = '".$ip."'  ".$record.$log_text;
				$this->executeQry($sql);
				$logid = $this->insert_id();
			}
			return $logid;
		} 
	}  

	// WhatsApp sender (provider-based). Configure constants in `includes/constant.php`.
	// Returns boolean success.
	function sendWhatsAppMessage(string $toMobile, string $message) : bool
	{
		if (WHATSAPP_ENABLED !== '1' || empty(WHATSAPP_API_URL) || empty(WHATSAPP_TOKEN) || empty(WHATSAPP_FROM)) {
			return false;
		}
		if (!function_exists('curl_init')) {
			return false;
		}

		$payload = array(
			'from' => WHATSAPP_FROM,
			'to' => $toMobile,
			'message' => $message
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, WHATSAPP_API_URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Bearer ' . WHATSAPP_TOKEN
		));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

		$resp = curl_exec($ch);
		$httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$curlErr = curl_error($ch);
		curl_close($ch);

		// Consider 2xx with non-empty response as success (provider can be refined later).
		if ($httpCode >= 200 && $httpCode < 300 && !empty($resp)) {
			return true;
		}
		if (!empty($curlErr)) {
			return false;
		}
		return false;
	}

	function get_user_ip()  
	{		
		$ip_address = "";
		//whether ip is from share internet
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   
		{
			$ip_address = $_SERVER['HTTP_CLIENT_IP'];
		}
		//whether ip is from proxy
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
		{
			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		//whether ip is from remote address
		else
		{
			$ip_address = $_SERVER['REMOTE_ADDR'];
		}
		return $ip_address;
	}
	
	/* function sendEmailNotifocation($subject, $body, $attachmentPath = '', $fromEmail = '', $fromName = '', $toEmail='',  $toName = '', $bcc = '') 
	{
		include_once('class.phpmailer.php');
		if ($fromName == '')
			$fromName = APPLICATION_FULL_NAME;
		$mail = new PHPMailer();
		$mail->From = FROM_EMAIL;
		$mail->AddReplyTo($fromEmail, $fromName);
		$mail->SMTPDebug  = 1;
		$mail->FromName = $fromName;
		$mail->Host = EMAIL_HOST;
		//$mail->Mailer = "smtp";
		$mail->SMTPAuth = "true";
		$mail->Port = 587;
		$mail->Username = USER_EMAIL;
		$mail->Password = MAIL_PASSWORD;
		$mail->ContentType = "text/html";
		$mail->Subject = $subject;
		$mail->Body = "<p>" . nl2br($body) . "</p>";
		//echo "<pre>";
		//print_r($mail); exit;
		if ($toEmail != '') {
			$toemail = explode(',', $toEmail);
			$toname = explode(',', $toName);
			$i = 0;
			foreach ($toemail as $email) {
				$name = @$toname[$i];
				if ($i == 0) {
					$mail->AddAddress($email, $name);
				} else {
					$mail->AddCC($email, $name);
				}
				$i++;
			}
		if($bcc != '')
		{
			$mail->addBCC($bcc);
		}
		
			if (is_array($attachmentPath)) {
				foreach ($attachmentPath as $attach) {
					$mail->AddAttachment($attach);
				}
			} else {
				$attach = $attachmentPath;
				if ($attach != '')
					$mail->AddAttachment($attach);
			}
			$response = $mail->Send();
			$mail->ClearAddresses();
			$mail->ClearAttachments();
			
		}
		return $response;
	} */
	function sendEmails(string $subject, $body, string $attachmentPath = '', string $fromEmail = '', string $fromName = '', string $toEmail = '',  string $toName = '', string $bcc = '')
	{
		$log = array();
		$logid = $this->Resquest_Response_log("", "EMAIL_NOTIFICATION", '', json_encode(array($toEmail,$subject)), '');
		//include_once('class.phpmailer.php');
		if (ENVIRONMENT == 'LOCAL') {
			return false;
		}
		if ($fromName == '') {
			$fromName = APPLICATION_FULL_NAME;
		}
		$mail = new PHPMailer\PHPMailer\PHPMailer(true);
		$mail->From = $fromEmail;
		$mail->AddReplyTo($fromEmail, $fromName);
		$mail->SMTPDebug  = 0;
		$mail->FromName = $fromName;
		$mail->Host = EMAIL_HOST;
		$mail->Port = 465;
		$mail->Username = USER_EMAIL;
		$mail->Password = MAIL_PASSWORD;
		$mail->ContentType = "text/html";
		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body = nl2br($body);
		if ($toEmail != '') {
			$toemail = explode(',', $toEmail);
			$toname = explode(',', $toName);
			$i = 0;
			foreach ($toemail as $email) {
				$name = @$toname[$i];
				if ($i == 0) {
					$mail->AddAddress($email, $name);
				} else {
					$mail->AddCC($email, $name);
				}
				$i++;
			}
			if ($bcc != '') {
				$mail->addBCC($bcc);
			}

			if (is_array($attachmentPath)) {
				foreach ($attachmentPath as $attach) {
					$mail->AddAttachment($attach);
				}
			} else {
				$attach = $attachmentPath;
				if ($attach != '')
					$mail->AddAttachment($attach);
			}
			$response = $mail->Send();
			$mail->ClearAddresses();
			$mail->ClearAttachments();
		}
		
		 $record_id = '1';
		$logid = $this->Resquest_Response_log($logid, '', $response, '', $record_id,array($mail,$toemail));
		unset($mail->Body);
		return $response;
	}
	function uploaddocs($filename,$allowed,$target_dir,$max_size,$prefix="",$db_path="") 
	{
		$response = array();
		$target_file = $target_dir . basename($filename["name"]);
		$file_ext = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		 
		if(!in_array($file_ext,$allowed) && count($allowed) > 0)
		{ 
			$response['upload'] = 0;
			$response['error'] = "File type not allowed.";
			$response['file_path'] = "";
		}
		else if($filename["size"] > $max_size)
		{
			$response['upload'] = 0;
			$response['error'] = "File size exceeded, only ".($max_size/1000)." KB File allowed.";
			$response['file_path'] = "";
		}
		else
		{
			$new_filename = $prefix.date('Ymdhis').".".$file_ext;     
			$target_file = $target_dir.$new_filename;	
			if(!empty($target_file))
			{
				if(move_uploaded_file($filename['tmp_name'], $target_file))
				{
					$response['upload'] = 1;
					$response['error'] = "";
					if($db_path != '')
					{
						$response['file_path'] = $db_path.$new_filename;
					}
					else
					{
						$response['file_path'] = $target_file;
					}
				}
				else
				{
					$response['upload'] = 0;
					$response['error'] = "Unable to upload file.";
					$response['file_path'] = "";
				}
			} 
		}
		return $response; 
	}
	function custompaging_table_response($cur_page, $no_of_paginations, $previous_btn, $next_btn, $first_btn, $last_btn, $current_page_url = '')
	{
		$msg = '';
		if ($cur_page >= 7) {
			$start_loop = $cur_page - 3;
			if ($no_of_paginations > $cur_page + 3)
				$end_loop = $cur_page + 3;
			else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
				$start_loop = $no_of_paginations - 6;
				$end_loop = $no_of_paginations;
			} else {
				$end_loop = $no_of_paginations;
			}
		} else {
			$start_loop = 1;
			if ($no_of_paginations > 7)
				$end_loop = 7;
			else
				$end_loop = $no_of_paginations;
		}
		/* ----------------------------------------------------------------------------------------------------------- */
		$msg .= "<ul class='pagination pagination-sm no-margin pull-right'>";



		//$url = $this->encode($current_page_url);
		$Qstring = $this->option($current_page_url);

		$new_url_array = array();
		foreach ($Qstring as $key => $value) {

			if ($key != 'cu_page') {
				if (in_array($key, array('pagetype'))) {
					$new_url_array[$key] = $value;
				} else {
					$new_url_array[$key] = $this->decode($value);
				}
			}
		}

		//echo '<pre>';
		//print_r($new_url_array);

		// FOR ENABLING THE FIRST BUTTON
		if ($first_btn && $cur_page > 1) {
			$new_url = APPLICATION_URL . "index.php?" . $this->encode(http_build_query($new_url_array) . "&cu_page=1");
			$msg .= "<li p='1' link='" . $new_url . "' class=' page-item actives'><a class='page-link' href='javascript:void(0)'>&lt;</a></li>";
		} else if ($first_btn) {
			$new_url = APPLICATION_URL . "index.php?" . $this->encode(http_build_query($new_url_array) . "&cu_page=1");
			$msg .= "<li p='1' link='" . $new_url . "' class=' page-item disabled'><a class='page-link' href='javascript:void(0)'>&lt;</a></li>";
		}

		// FOR ENABLING THE PREVIOUS BUTTON
		if ($previous_btn && $cur_page > 1) {
			$pre = $cur_page - 1;
			$new_url = APPLICATION_URL . "index.php?" . $this->encode(http_build_query($new_url_array) . "&cu_page=" . $pre);
			$msg .= "<li p='$pre' link='" . $new_url . "' class='page-item actives'><a  class='page-link' href='javascript:void(0)'>&lt;&lt;</a></li>";
		} else if ($previous_btn) {
			$msg .= "<li class='page-item disabled'><a class='page-link' href='javascript:void(0)'>&lt;&lt;</a></li>";
		}
		for ($i = $start_loop; $i <= $end_loop; $i++) {

			if ($cur_page == $i) {
				$new_url = APPLICATION_URL . "index.php?" . $this->encode(http_build_query($new_url_array) . "&cu_page=" . $i);
				$msg .= "<li p='$i' link='" . $new_url . "' class='page-item active'><a class='page-link' href='javascript:void(0)'>{$i}</a></li>";
			} else {
				$new_url = APPLICATION_URL . "index.php?" . $this->encode(http_build_query($new_url_array) . "&cu_page=" . $i);
				$msg .= "<li p='$i' link='" . $new_url . "' class='page-item actives'><a  class='page-link' href='javascript:void(0)'>{$i}</a></li>";
			}
		}

		// TO ENABLE THE NEXT BUTTON
		if ($next_btn && $cur_page < $no_of_paginations) {
			$nex = $cur_page + 1;
			$new_url = APPLICATION_URL . "index.php?" . $this->encode(http_build_query($new_url_array) . "&cu_page=" . $nex);
			$msg .= "<li p='$nex' link='" . $new_url . "' class='page-item actives'><a  class='page-link' href='javascript:void(0)'>&gt;&gt;</a></li>";
		} else if ($next_btn) {
			$msg .= "<li class='page-item disabled'><a class='page-link' href='javascript:void(0)'>&gt;&gt;</a></li>";
		}

		// TO ENABLE THE END BUTTON
		if ($last_btn && $cur_page < $no_of_paginations) {
			$new_url = APPLICATION_URL . "index.php?" . $this->encode(http_build_query($new_url_array) . "&cu_page=" . $cur_page);
			$msg .= "<li p='$no_of_paginations' link='" . $new_url . "' class='page-item actives'><a  class='page-link' href='javascript:void(0)'>&gt;</a></li>";
		} else if ($last_btn) {
			$new_url = APPLICATION_URL . "index.php?" . $this->encode(http_build_query($new_url_array) . "&cu_page=" . $cur_page);
			$msg .= "<li p='$no_of_paginations' link='" . $new_url . "' class='page-item disabled'><a  class='page-link' href='javascript:void(0)'>&gt;</a></li>";
		}
		$msg = $msg . "</ul>"; // Content for pagination
		return $msg;
	}
	
	function custompaging($cur_page, $no_of_paginations, $previous_btn, $next_btn, $first_btn, $last_btn) {
    $msg = '';
    if ($cur_page >= 7) {
        $start_loop = $cur_page - 3;
        if ($no_of_paginations > $cur_page + 3)
            $end_loop = $cur_page + 3;
        else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
            $start_loop = $no_of_paginations - 6;
            $end_loop = $no_of_paginations;
        } else {
            $end_loop = $no_of_paginations;
        }
    } else {
        $start_loop = 1;
        if ($no_of_paginations > 7)
            $end_loop = 7;
        else
            $end_loop = $no_of_paginations;
    }
    /* ----------------------------------------------------------------------------------------------------------- */
    $msg .= "<ul class='pagination pagination-sm no-margin pull-right'>";

// FOR ENABLING THE FIRST BUTTON
    if ($first_btn && $cur_page > 1) {
        $msg .= "<li p='1' class=' page-item actives'><a class='page-link' href='javascript:void(0)'>First</a></li>";
    } else if ($first_btn) {
        $msg .= "<li p='1' class=' page-item disabled'><a class='page-link' href='javascript:void(0)'>First</a></li>";
    }

// FOR ENABLING THE PREVIOUS BUTTON
    if ($previous_btn && $cur_page > 1) {
        $pre = $cur_page - 1;
        $msg .= "<li p='$pre' class='page-item actives'><a class='page-link' href='javascript:void(0)'>&lt;&lt;Previous</a></li>";
    } else if ($previous_btn) {
        $msg .= "<li class='page-item disabled'><a class='page-link' href='javascript:void(0)'>&lt;&lt;Previous</a></li>";
    }
    for ($i = $start_loop; $i <= $end_loop; $i++) {

        if ($cur_page == $i)
            $msg .= "<li p='$i' class='page-item active'><a class='page-link' href='javascript:void(0)'>{$i}</a></li>";
        else
            $msg .= "<li p='$i' class='page-item actives'><a class='page-link' href='javascript:void(0)'>{$i}</a></li>";
    }

// TO ENABLE THE NEXT BUTTON
    if ($next_btn && $cur_page < $no_of_paginations) {
        $nex = $cur_page + 1;
        $msg .= "<li p='$nex' class='page-item actives'><a class='page-link' href='javascript:void(0)'>Next&gt;&gt;</a></li>";
    } else if ($next_btn) {
        $msg .= "<li class='page-item disabled'><a class='page-link' href='javascript:void(0)'>Next&gt;&gt;</a></li>";
    }

// TO ENABLE THE END BUTTON
    if ($last_btn && $cur_page < $no_of_paginations) {
        $msg .= "<li p='$no_of_paginations' class='page-item actives'><a class='page-link' href='javascript:void(0)'>Last</a></li>";
    } else if ($last_btn) {
        $msg .= "<li p='$no_of_paginations' class='page-item disabled'><a class='page-link' href='javascript:void(0)'>Last</a></li>";
    }
    $msg = $msg . "</ul>";  // Content for pagination
    return $msg;
}


function custompaging_new($cur_page, $no_of_paginations, $previous_btn, $next_btn, $first_btn, $last_btn,$stat='projects',$ff1='',$ffv1='',$ff2='',$ffv2='',$ff3='',$ffv3='',$ff4='',$ffv4='',$ff5='',$ffv5='') {
		 
    $msg = '';
    if ($cur_page >= 7) {
        $start_loop = $cur_page - 3;
        if ($no_of_paginations > $cur_page + 3)
            $end_loop = $cur_page + 3;
        else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
            $start_loop = $no_of_paginations - 6;
            $end_loop = $no_of_paginations;
        } else {
            $end_loop = $no_of_paginations;
        }
    } else {
        $start_loop = 1;
        if ($no_of_paginations > 7)
            $end_loop = 7;
        else
            $end_loop = $no_of_paginations;
    }
    /* ----------------------------------------------------------------------------------------------------------- */
    $msg .= "<ul class='pagination pagination-sm no-margin pull-right'>";
	
	 

// FOR ENABLING THE FIRST BUTTON
    if ($first_btn && $cur_page > 1) {
        $msg .= "<li p='1' class=' page-item actives'><a class='page-link' href=".APP_URL.'index.php?'.$this->encode('stat='.$stat.'&pageno=1&'.$ff1.'='.$ffv1.'&'.$ff2.'='.$ffv2.'&'.$ff3.'='.$ffv3.'')." >First</a></li>";
    } else if ($first_btn) {
        $msg .= "<li p='1' class=' page-item disabled'><a class='page-link' href=".APP_URL.'index.php?'.$this->encode('stat='.$stat.'&pageno=1')." >First</a></li>";
    }

// FOR ENABLING THE PREVIOUS BUTTON
    if ($previous_btn && $cur_page > 1) {
        $pre = $cur_page - 1;
        $msg .= "<li p='$pre' class='page-item actives'><a class='page-link' href=".APP_URL.'index.php?'.$this->encode('stat='.$stat.'&pageno='.$pre.'&'.$ff1.'='.$ffv1.'&'.$ff2.'='.$ffv2.'&'.$ff3.'='.$ffv3.'')." >&lt;&lt;Previous</a></li>";
    } else if ($previous_btn) {
        $msg .= "<li class='page-item disabled'><a class='page-link' href='javascript:void(0)'>&lt;&lt;Previous</a></li>";
    }
    for ($i = $start_loop; $i <= $end_loop; $i++) {

        if ($cur_page == $i)
            $msg .= "<li p='$i' class='page-item active'><a class='page-link' href=".APP_URL.'index.php?'.$this->encode('stat='.$stat.'&pageno='.$i.'&'.$ff1.'='.$ffv1.'&'.$ff2.'='.$ffv2.'&'.$ff3.'='.$ffv3.'')." >{$i}</a></li>";
        else
            $msg .= "<li p='$i' class='page-item actives'><a class='page-link' href=".APP_URL.'index.php?'.$this->encode('stat='.$stat.'&pageno='.$i.'&'.$ff1.'='.$ffv1.'&'.$ff2.'='.$ffv2.'&'.$ff3.'='.$ffv3.'')." >{$i}</a></li>";
    }

// TO ENABLE THE NEXT BUTTON
    if ($next_btn && $cur_page < $no_of_paginations) {
        $nex = $cur_page + 1;
        $msg .= "<li p='$nex' class='page-item actives'><a class='page-link' href=".APP_URL.'index.php?'.$this->encode('stat='.$stat.'&pageno='.$nex.'&'.$ff1.'='.$ffv1.'&'.$ff2.'='.$ffv2.'&'.$ff3.'='.$ffv3.'').">Next&gt;&gt;</a></li>";
    } else if ($next_btn) {
        $msg .= "<li class='page-item disabled'><a class='page-link' href='javascript:void(0)'>Next&gt;&gt;</a></li>";
    }

// TO ENABLE THE END BUTTON
    if ($last_btn && $cur_page < $no_of_paginations) {
        $msg .= "<li p='$no_of_paginations' class='page-item actives'><a class='page-link'  href=".APP_URL.'index.php?'.$this->encode('stat='.$stat.'&pageno='.$no_of_paginations.'&'.$ff1.'='.$ffv1.'&'.$ff2.'='.$ffv2.'&'.$ff3.'='.$ffv3.'').">Last</a></li>";
    } else if ($last_btn) {
        $msg .= "<li p='$no_of_paginations' class='page-item disabled'><a class='page-link' href='javascript:void(0)'>Last</a></li>";
    }
    $msg = $msg . "</ul>";  // Content for pagination
    return $msg;
}


 

function autocommit($opt)
{
	return mysqli_autocommit($this->cnx, $opt);
}

function commit()
{
	return mysqli_commit($this->cnx);
}

function rollback()
{
	return mysqli_rollback($this->cnx);
}

function validate_recaptcha($google_post)
{
	$ans = 0;
	if(isset($_POST['captcha']))
	{
		$captcha = $_POST['captcha'];
		if($captcha == $_SESSION["vercode"])
		{
			$ans = 1;
		}
	}
	else
	{
		$secret = SECRET_KEY;
		$url =  "https://www.google.com/recaptcha/api/siteverify";
		$data = array(
				'secret' => $secret,
				'response' => $google_post
			); 
		$verify = curl_init();
		curl_setopt($verify, CURLOPT_URL, $url);
		curl_setopt($verify, CURLOPT_POST, true);
		curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($verify);
		$responseData = json_decode($response);
		if($responseData->success)  
		{
			$ans = 1;		
		} 
	}
	
	return $ans;
}

function cleanMe($input) 
	{
		$input = $this->escape($input); 
		$input = htmlspecialchars($input, ENT_IGNORE, 'utf-8');
		$input = strip_tags($input);
		$input = stripslashes($input); 
		$input = $this->escape($input); 
		return $input;
	}


function callCURL($method, $url, $data, $headers, $request_type='', $record_id='')
	{
		print_r($url);exit;
		//$headers = array('APIKEY: 111111111111111111111', 'Content-Type: application/json');
		$requestStr = json_encode(func_get_args()); 
		$logid = $this->Resquest_Response_log('', $request_type, '', $requestStr, $record_id);
		$curl = curl_init();
		switch ($method){
		   case "POST":
			  curl_setopt($curl, CURLOPT_POST, 1);
			  if ($data)
				 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			  break;
		   case "PUT":
			  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
			  if ($data)
				 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
			  break;
		   default:
			  if ($data)
				 $url = sprintf("%s?%s", $url, http_build_query($data));
		}
		// OPTIONS:
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		// EXECUTE:
		$response = curl_exec($curl);
		$curl_getinfo = curl_getinfo($curl);	
		$response_array = json_decode($response, true);
		if(is_array($response_array) && isset($curl_getinfo['http_code']) && $curl_getinfo['http_code'] != '')
		{
			$response_array['http_code'] = $curl_getinfo['http_code'];
		}
		else
		{
			$response_array = array();
			$response_array['http_code'] = '';
		}
		
		curl_close($curl);
		$result = json_encode($response_array);
		$logid = $this->Resquest_Response_log($logid,'', $result, '', '');
		return $result;
	}
  
function arrayToString($str)
{
	$response  = '';
	if( is_array($str))
	{
		$response = implode(', ', $str);
	}
	else
	{
		$response = (!empty($str)) ? $str : '-';
	}
	if($response == '')
	{
		$response =  '-';
	}
	return $response; 
}



function signup_email_tmpl($contact_name, $username, $password, $source="")
{
	$history_link = APP_URL.'index.php?'.$this->encode("stat=new_order");
	$trial = "";
	if($source == 'LIVE_TRIAL')
	{
		$trial_end_date = $this->formatdate(date('Y-m-d',strtotime('+'.DEFAULT_TRIAL_DAYS.' days')), "j-M-Y");
		$trial = "<p><b>This is a trial account which will be deactivated automatically on ".$trial_end_date.".</b></p>";
	}
	
	$body = '<table class="main" style="margin-top:10px !important; width: 100%; background: #e4dede; border-radius: 3px;"> <tr>
		<td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
		  <table border="0" cellpadding="10" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color:#fff;">
			<tr> 
			  <td style="font-family: sans-serif; font-size: 16px; vertical-align: top;">
				<p width="30%" style="width:25% !important;" ><img width="25%" style="width:25% !important;" src="'.APP_URL.LOGO_PATH.'" /></p>
				<p style="font-family: sans-serif; font-size: 16px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Welcome  '.ucwords($contact_name).' ,</br>
				We are so happy to have you on-boarded! </br>
				Now you can get your own AI based search done <a href="'.$history_link.'">here</a> </br></br>'.$trial.'</br>If you have any questions regarding your account, click <b>Reply</b> on your email and we will be happy to help.</br> </br> For your record, here is a copy of the information you submitted: <p>User Name : <b><u>'.$username.'</u></b></p><p>Password : <b><u>'.$password.'</u></b></p></p>
				
				<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Thank you,<p>'.APP_NAME.' Team</p></p></br>
				
			  </td>
			</tr>
	</table></td>
		</tr>
	</table>';
	return $body;
}

function forgot_password_email_tmpl($contact_name, $password)
{
	$body = '<table class="main" style="margin-top:10px !important; width: 100%; background: #e4dede; border-radius: 3px;"> <tr>
		<td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">  
		  <table border="0" cellpadding="30" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color:#fff;">
			<tr>
			  <td style="font-family: sans-serif; font-size: 16px; vertical-align: top;">
				<p width="30%" style="width:30% !important;" ><img width="50%" style="50% !important;" src="'.APP_URL.LOGO_PATH.'" /></p>
				<p style="font-family: sans-serif; font-size: 16px; font-weight: normal; margin: 0; ">Hi  '.ucwords($contact_name).' ,</p>
				<p>We have successfully reset your password.</p>
				<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">If you have any questions regarding your account, click <b>Reply</b> on your email and we will be happy to help.</p>
				<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Your new password is :  <b><u>'.$password.'</u></b></p>
				
				<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Thank you,<p>'.APP_NAME.' Team</p></p></br>
				<p><center><span  style="color: #999999; font-size: 12px; text-align: center;"><h2>"'.APP_FULL_NAME.'"</h2></span></center></p>
			  </td>
			</tr>
	</table></td>
		</tr>
	</table>';
	return $body;
}

function getResultsArray(string $tbl, string $fields, $con)
{
  	$query = "select ".$fields. " from "  .$tbl.  " where ".$con;
    $result = $this->executeQry($query);
	if($result)
	{
		$rows = $this->getTotalRow($result);
	}
	else
	{
		$rows = 0;
	}
    
    $response = array();
	if($rows>0)
	{
		while($rows = $this->fetch_assoc($result))
		{			
			$response[] = $rows;		
		}
	}
	return $response;	
}

function generateCSRF($action)
{
	if($action == "GENERATE")
	{
		$length = 32;
		$_SESSION['csrf_token'] = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length); 
		// 1 hour = 60 seconds * 60 minutes = 3600
		$_SESSION['token-expire'] = time() + 1800;
		return $_SESSION['csrf_token'];
	}
	else
	{
		// THEN CHECK FOR THE EXPIRY ON REQUEST
		if (isset($_SESSION['csrf_token']) && $_SESSION['csrf_token']==$_POST['token']) 
		{
			if (time() >= $_SESSION['token-expire'])
			{
				return false;
			} 
			else 
			{
				return true;
			}
		}
	}
}
function generate_family_id()
{
    $generate_num = '';
    $sql = "SELECT (id+1) as id FROM ".FAMILY_ID." WHERE 1 order by id DESC LIMIT 1";
    $result = $this->executeQry($sql);
    if($this->num_rows($result) > 0)
    {
        $row = $this->fetch_object($result);
        if($row->id > 0)
        {
           
            $generate_num = date('y').'-'.$row->id;
            
        }
    }else{
		$generate_num = date('y').'- 1';
	}
	return $generate_num;
}
  function create_pdf($content,$filepath,$papersize)
{
$dompdf = new Dompdf();
$dompdf->loadHtml($content);
$paper_size = isset($papersize) ? strtoupper(trim((string)$papersize)) : '';
if ($paper_size === 'A5') {
        $dompdf->setPaper('A5', 'portrait');
    } else {
        $dompdf->setPaper('A4', 'portrait');
    }
$dompdf->set_option( 'defaultFont' , 'Courier' );
$dompdf->set_option( 'isRemoteEnabled' , TRUE );
$dompdf->set_option( 'debugKeepTemp' , TRUE );
$dompdf->set_option( 'isHtml5ParserEnabled' , true );
$dompdf->set_option('DOMPDF_UNICODE_ENABLED', 'true');
$dompdf->render();
//$dompdf->stream(); exit;
$output = $dompdf->output();
file_put_contents($filepath , $output);
if(file_exists($filepath))
{
return 1;
}
else
{
return 0;
}
} 
function generate_membership_id()
{
    $generate_num = '';
    $sql = "SELECT (id+1) as id FROM ".MEMBERS." WHERE 1 order by id DESC LIMIT 1";
    $result = $this->executeQry($sql);
    if($this->num_rows($result) > 0)
    {
        $row = $this->fetch_object($result);
        if($row->id > 0)
        {
           
            $generate_num = date('y').'-'.$row->id;
            
        }
    }else{
		$generate_num = date('y').'- 1';
	}
	return $generate_num;
}
function xss_clean($data)
{
        // Fix &entity\n;
        $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do
        {
                // Remove really unwanted tags
                $old_data = $data;
                $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        }
        while ($old_data !== $data);

        // we are done...
        return $data;
}

function RemoveSpecialChapr($str)
	{
		$str = str_replace("\n", '', str_replace("\r", '', $str));		
		return $str;
	}
function escapeJsonString($value) { # list from www.json.org: (\b backspace, \f formfeed)
    $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
    $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
    $result = str_replace($escapers, $replacements, $value);
    return $result;
}
 /* function create_pdf($content, $filepath)
{
        $dompdf = new Dompdf();
        $dompdf->loadHtml($content);
        $dompdf->setPaper('A4');
        $dompdf->set_option('defaultFont', 'Courier');
        $dompdf->set_option('isRemoteEnabled', true);
        $dompdf->set_option('debugKeepTemp', true);
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('DOMPDF_UNICODE_ENABLED', true);
        $dompdf->render();
        
        $output = $dompdf->output();
        
        // Attempt to write the file
        if (file_put_contents($filepath, $output) !== false) {
            return 1; // File successfully created
        } else {
            return 0; // File creation failed
        }
   
}
 */
 
function RecTimeStamp($format)
{
	$zone = 3600*+5.45;	
	$date = gmdate($format, time() + $zone);		
	return $date;
}

function prompt($prompt_msg){
        echo("<script type='text/javascript'> var answer = confirm('".$prompt_msg."'); </script>");
        $answer = "<script type='text/javascript'> document.write(answer); </script>";
        return($answer);
    }

function decimalHours($time)
{
	$hms = explode(":", $time);
	//return ($hms[0] + ($hms[1]/60) + ($hms[2]/3600));
	return ($hms[0].".".$hms[1]);
}
		
function decimalHoursOnly($time)
{
	$hms = explode(":", $time);
	return ($hms[0]);
}


function Getdatediff($date1,$date2)
{
	$date_a = new DateTime($date1);
	$date_b = new DateTime($date2);
	$interval = date_diff($date_a,$date_b);
	$time = $interval->format('%h:%i:%s');
	return $time;
}
		
 function Getdatediff_new($date1,$date2)
{
	$date_a = new DateTime($date1);
	$date_b = new DateTime($date2);
	$interval = date_diff($date_a,$date_b);
	$hms = explode(":", $interval);
	//$time = (int) $interval->format('%h.%i');
	$totaltime = ($hms[0].".".$hms[1]);
	return $totaltime;
}

function date_diff_in_days($date1,$date2)
{

	$date1=date_create($date1);
	$date2=date_create($date2);
	$diff=date_diff($date1,$date2);
	$days = $diff->format("%a")+1;
	return $days;
}

function isJson($string) {
   json_decode($string);
   return json_last_error() === JSON_ERROR_NONE;
}


function get1MGUrlFromGoogle($title){
    $url = "http://www.google.com/search?q=substitute + of + ".rawurlencode($title)." + 1 MG";
    echo $html = $this->geturl($url);
	$urls = $this->match_all('/<a href="(http:\/\/www.1mg.com\/drugs\/)".*?>.*?<\/a>/ms', $html, 1);
	print_r($urls);
    if (!isset($urls[0]))
        return NULL;
    else
        return $urls[0]; //return first 1mg result

}  

function get1MgUrl($title)
{
    include_once(ABSOLUTE_ROOT_PATH.'simple_html_dom.php');
	$url = "http://www.google.com/search?q=substitute+of+".rawurlencode($title)."+1MG";
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 5.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1");
    $result = curl_exec($ch);
    curl_close($ch);
	$domResult = new simple_html_dom();
	$domResult->load($result);
	
	 
	foreach($domResult->find('a[href^=/url?]') as $link)
	{
		
		if(strpos($link->href,"1mg.com") !== false)
		{
			$link_only = explode("&",$link->href);
			
			return str_replace("/url?q=","",$link_only[0]);
		}
	}
}

function verify_otp($otp)
{
	$RecTimeStamp = $this->RecTimeStamp("Y-m-d");
	$otp_verify = false;
	$expr = '/^[1-9][0-9]*$/';
	if (preg_match($expr,$otp) && filter_var($otp, FILTER_VALIDATE_INT))
	{
		$first = substr($otp,0,2);
		$last_arr = explode($first,$otp);
		$last = $last_arr[1]; 
		$num = (($last+12)/3)+5;	
		
		if(($num - $first) == 8)
		{
			$before15days = Date('Y-m-d',strtotime('-2 days',strtotime($RecTimeStamp)))." 00:00:00";
			$current = $RecTimeStamp." 23:59:59";
			$sql = "SELECT * FROM tbl_attendance_otp WHERE otp = '".$otp."' and rectimestamp >= '".$before15days."' and rectimestamp <= '".$current."'";
			$result = $this->executeQry($sql);
			if($this->num_rows($result))
			{
				$otp_verify = false;
			}
			else
			{
				$otp_verify = true;
			}
		}
	}
	return $otp_verify;
}
function generateInvoice($id) {

    $file_name = '';
    $html_content = '';

    // Fetch member
    $sql = "SELECT * FROM " . MEMBERS . " WHERE id ='".$id."'";
    $result = $this->executeQry($sql);
    $rows = $this->fetch_assoc($result);

    // Fetch plan
    $plan_details = $this->singleRowAssoc_new('*', PLANS, 'id = "'.$rows['plan_id'].'"');

    // Membership Type (Dynamic)
    $membership_type = $rows['membership_type'];

    // Generate Invoice No
    $invoice_no = 'INV-' . str_pad($rows['id'], 5, '0', STR_PAD_LEFT);

    $file_name = 'Invoice_' . date("Ymd") . rand() . ".pdf";

    // Professional HTML
    $html_content = '
    <!doctype html>
    <html>
    <head>
    <meta charset="UTF-8">
    <style>
    body { font-family: Arial, sans-serif; color:#333; font-size:12px; }
    .container { padding:20px; }
    .header { display:flex; justify-content:space-between; align-items:center; }
    .logo { width:80px; }
    .title { font-size:22px; font-weight:bold; }
    .section { margin-top:20px; }
    table { width:100%; border-collapse: collapse; margin-top:10px; }
    th, td { padding:10px; border:1px solid #ddd; }
    th { background:#f2f2f2; }
    .right { text-align:right; }
    .bold { font-weight:bold; }
    .summary td { border:none; padding:6px; }
    .highlight { background:#fafafa; font-weight:bold; }
    </style>
    </head>

    <body>
    <div class="container">

        <!-- Header -->
        <div class="header">
            <div>
                <img src="' . APPLICATION_URL . 'backend/images/logos/logo112.png" class="logo">
                <h2>'.COMPANY_NAME.'</h2>
            </div>
            <div class="right">
                <div class="title">INVOICE</div>
                <div><b>Invoice No:</b> '.$invoice_no.'</div>
                <div><b>Date:</b> '.date('d M Y').'</div>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="section">
            <b>Billed To:</b><br>
            '.$rows['name'].'<br>
            '.$rows['email'].'<br>
            '.$rows['mobile'].'<br>
            <b>Membership ID:</b> '.$rows['member_id'].'
        </div>

        <!-- Membership Details -->
        <div class="section">
            <b>Membership Details</b>
            <table>
                <tr>
                    <th>Plan</th>
                    <th>Membership Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
                <tr>
                    <td>'.$plan_details['title'].'</td>
                    <td>'.$membership_type.'</td>
                    <td>'.$rows['start_date'].'</td>
                    <td>'.$rows['end_date'].'</td>
                </tr>
            </table>
        </div>

        <!-- Payment Details -->
        <div class="section">
            <b>Payment Summary</b>
            <table>
                <tr>
                    <th>Total Amount</th>
                    <th>Paid</th>
                    <th>Pending</th>
                    <th>Status</th>
                </tr>
                <tr>
                    <td class="right">'.$rows['total_amount'].'</td>
                    <td class="right">'.$rows['paid_amount'].'</td>
                    <td class="right">'.$rows['pending_amount'].'</td>
                    <td>'.$rows['payment_status'].'</td>
                </tr>
            </table>
        </div>

        <!-- Summary -->
        <div class="section right">
            <table class="summary">
                <tr>
                    <td>Total:</td>
                    <td>'.$rows['total_amount'].'</td>
                </tr>
                <tr>
                    <td>Paid:</td>
                    <td>'.$rows['paid_amount'].'</td>
                </tr>
                <tr class="highlight">
                    <td>Pending:</td>
                    <td>'.$rows['pending_amount'].'</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="section">
            <p>Thank you for choosing <b>'.COMPANY_NAME.'</b>.</p>
            <p><small>Generated on '.date('d/m/Y H:i').'</small></p>
        </div>

    </div>
    </body>
    </html>';

    // Generate PDF
    $file_path = ABSOLUTE_ROOT_INV . $file_name;
    $this->create_pdf($html_content, $file_path, 'A4');

    return $file_name;
}
function createcookie($name,$value)
{
	$timeout = time()+60*60*24*15;
	setcookie($name,"$value",$timeout);	
}
function deletecookie($name)
{
	$timeout = time() - 60 * 60 * 24 * 16;
    setcookie($name,'',$timeout);
}


	function add_remove_ip($ip_adress,$action)
	{
		copy('.htaccess', 'htaccess_bkp');
		$rules = "Require ip ".$ip_adress."\n";
		$htaccess = file_get_contents('.htaccess');
		if($action == "add")
		{
			$htaccess = str_replace('###CUSTOM RULES###', $rules."\n###CUSTOM RULES###", $htaccess);			
		}
		else if($action == "remove")
		{
			$htaccess = str_replace($rules, "", $htaccess);
		}
		#echo $rules;
		#echo "#############";
		#echo $htaccess; exit;
		file_put_contents('.htaccess', $htaccess);
	}
	
	function formatPhoneNumber($phoneNumber) 
	{
		$phoneNumber = preg_replace('/[^0-9]/','',$phoneNumber);

		if(strlen($phoneNumber) > 10) {
			$countryCode = substr($phoneNumber, 0, strlen($phoneNumber)-10);
			$areaCode = substr($phoneNumber, -10, 3);
			$nextThree = substr($phoneNumber, -7, 3);
			$lastFour = substr($phoneNumber, -4, 4);

			$phoneNumber = '+'.$countryCode.' ('.$areaCode.') '.$nextThree.'-'.$lastFour;
		}
		else if(strlen($phoneNumber) == 10) {
			$areaCode = substr($phoneNumber, 0, 3);
			$nextThree = substr($phoneNumber, 3, 3);
			$lastFour = substr($phoneNumber, 6, 4);

			$phoneNumber = '('.$areaCode.') '.$nextThree.'-'.$lastFour;
		}
		else if(strlen($phoneNumber) == 7) {
			$nextThree = substr($phoneNumber, 0, 3);
			$lastFour = substr($phoneNumber, 3, 4);

			$phoneNumber = $nextThree.'-'.$lastFour;
		}

		return $phoneNumber;
	}

	function blockUnblockUser($apiKey, $employeeCode, $employeeName, $serialNumber, $isBlock, $userName, $userPassword, $commandId) {
			$log =array();
			// The URL of the web service
			//$url = 'http://192.168.1.107:85/iclock/WebAPIService.asmx';
			$url = 'http://166.0.244.12:82/iclock/WebAPIService.asmx';
			// The XML payload with dynamic data
			 $xmlPayload = <<<XML
		<?xml version="1.0" encoding="utf-8"?>
		<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
		  <soap:Body>
			<BlockUnblockUser xmlns="http://tempuri.org/">
			  <APIKey>$apiKey</APIKey>
			  <EmployeeCode>$employeeCode</EmployeeCode>
			  <EmployeeName>$employeeName</EmployeeName>
			  <SerialNumber>$serialNumber</SerialNumber>
			  <IsBlock>$isBlock</IsBlock>
			  <UserName>$userName</UserName>
			  <UserPassword>$userPassword</UserPassword>
			  <CommandId>$commandId</CommandId>
			</BlockUnblockUser>
		  </soap:Body>
		</soap:Envelope>
		XML;
		//print_r($xmlPayload);exit;

			$ch = curl_init($url);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'Content-Type: text/xml; charset=utf-8',
				'SOAPAction: "http://tempuri.org/BlockUnblockUser"'
			]);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlPayload);
				
			$response = curl_exec($ch);
			
			if (curl_errno($ch)) {
				$error_msg = curl_error($ch);
				$bio_response = $error_msg;
			} else {
				$bio_response = $response;
			}
			$log['biometric'] = str_replace(["\r", "\n", "\t"], '', $xmlPayload);
			$logid = $this->Resquest_Response_log('0', 'BIOMETRIC', $bio_response, 'Biometric_command', '', $log);
			curl_close($ch);
			return $bio_response;
	}
	function addEmployee($apiKey, $employeeCode, $employeeName, $cardNumber, $serialNumber, $userName, $userPassword, $commandId) {
			$log =array();
			// The URL of the web service
			$url = 'http://166.0.244.12:82/iclock/WebAPIService.asmx';
			//$url = 'http://http://182.76.161.219:81/iclock/WebAPIService.asmx';

			// The XML payload with dynamic data
			$xmlPayload = <<<XML
		<?xml version="1.0" encoding="utf-8"?>
		<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
		  <soap:Body>
				<AddEmployee xmlns="http://tempuri.org/">
			  <APIKey>$apiKey</APIKey>
			  <EmployeeCode>$employeeCode</EmployeeCode>
			  <EmployeeName>$employeeName</EmployeeName>
			  <CardNumber>$cardNumber</CardNumber>
			  <SerialNumber>$serialNumber</SerialNumber>
			  <UserName>$userName</UserName>
			  <UserPassword>$userPassword</UserPassword>
			  <CommandId>$commandId</CommandId>
			</AddEmployee>
		  </soap:Body>
		</soap:Envelope>
		XML;
			$ch = curl_init($url);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'Content-Type: text/xml; charset=utf-8',
				'SOAPAction: "http://tempuri.org/AddEmployee"'
			]);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlPayload);

			$response = curl_exec($ch);
			
			if (curl_errno($ch)) {
				$error_msg = curl_error($ch);
				$bio_response = $error_msg;
			} else {
				$bio_response = $response;
			}
			$log['biometric'] = str_replace(["\r", "\n", "\t"], '', $xmlPayload);
			$logid = $this->Resquest_Response_log('0', 'BIOMETRIC', $bio_response, 'Biometric_command', '', $log);
			curl_close($ch);
			return $bio_response;
	}
	
	
}

?>

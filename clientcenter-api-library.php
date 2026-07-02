<?php

date_default_timezone_set('Canada/Eastern');

class clientcenter

{

	public $client_code;	 

	public $fname;

	public $lname;

	public $email;

	public $comments;

	public $phone;

	public $status = 1;

	public $domain;

	public $company_name;

	public $address_1;

	public $country;

	public $city;

	public $state;

	public $postalcode;

	public $budget;

	public $useragent;

	public $referrer;

	public $remote_ip;

	public $contact_date;

	public $search_engine;

	public $keyword;

	public $tag;

	public $uid;

	public $source;

	public $randomnum;

	public $adl_ref;

	public $company;

	public $visitor_tracking_Id;

	public $other_fields;

	public $gclid;

	public $send_to_adluge=true;

	public $send_to_techwyse=true;

	public $adl_source;



	public $response;



	public function __construct()

	{

		$this->useragent = $_SERVER['HTTP_USER_AGENT'];

		$this->remote_ip=$_SERVER['REMOTE_ADDR'];

		$this->referrer=@$_SERVER['HTTP_REFERER'];

		$this->contact_date=date("Y-m-d h:i:s");

		$this->visitor_tracking_Id = $this->getCookieName();

	}



	public function getCookieName()

	{

		foreach ($_COOKIE as $key=>$val)

		{

		if(strpos($key,'_adl_id_')!== false)

		return $_COOKIE[$key];

		else

		$adl_cookie_val = "No Cookie";

		}

		return $adl_cookie_val;

	}

	public function send()

	{		



		$params=array(



						"fname"=>$this->fname,

						"lname"=>$this->lname,

						"email"=>$this->email,

						"comments"=>$this->comments,

						"phone"=>$this->phone,

						"status"=>$this->status,

						"client_code"=>$this->client_code,

						"lead_domain"=>$this->domain,

						"lead_company_name"=>$this->company_name,

						"lead_address"=>$this->address_1,

						"lead_country"=>$this->country,

						"lead_city"=>$this->city,

						"lead_postcode"=>$this->postalcode,

						"lead_state"=>$this->state,

						"budget"=>$this->budget,

						"gclid" => $this->gclid,

						"contact_date"=>$this->contact_date,

						"useragent"=>$this->useragent,

						"referer"=>$this->referrer,

						"remoteip"=>$this->remote_ip,

						"search_engine"=>$this->search_engine,

						"keyword"=>$this->keyword,

						"tag_name"=>$this->tag,

						"UID"=>$this->uid,

						"source"=>$this->source,

						"randomnum"=>$this->randomnum,

						"adl_ref"=>$this->adl_ref,

						"adl_source"=>$this->adl_source,

						"lead_sessionid" => $this->visitor_tracking_Id,

						"other_fields" => $this->other_fields,

					  );



		$this->validation();



		if(!function_exists('curl_init'))

			die("You need to install curl library.");

			

		if($this->send_to_adluge)

		{

			$ch=curl_init('http://www.adluge.com/clientcenter/api/addlead.php');

			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

			curl_setopt($ch,CURLOPT_POST,1);

			curl_setopt($ch,CURLOPT_POSTFIELDS,$params);

			$result_adluge=@curl_exec($ch);

			$info_adluge = curl_getinfo($ch);		

//			@$this->response->adluge =array("data"=>$result_adluge,"response_code"=>$info_adluge);

			curl_close($ch);

			unset($ch);

		//	return $this->response->adluge;

		}

	}



	public function validation()

	{

		if(trim($this->client_code)=='')

			die("Client code cannot be empty.");

	}





}

?>


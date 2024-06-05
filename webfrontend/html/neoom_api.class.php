<?php
 require_once "loxberry_log.php";
 
class neoom_api {

	protected $apikey;
	protected $sid;
	protected $token;
	protected $beaamIP;
	

	function login($apikey, $beaamIP)
	{
		
        LOGDEB("Calling Logon to NEOOM API");	
		
        $this->apikey = $apikey;
		$this->beaamIP = $beaamIP;
		
	}

	private function get_headers()
	{
		if ( isset($this->apikey) )
		{
			$generique_headers = array(
			    'Content-type: application/json',
			    'Accept: application/json',
				'Authorization: Bearer '.$this->apikey
			);
		}
		else
		{
			$generique_headers = array(
			   'Content-type: application/json',
			   'Accept: application/json'
			   );
		}
		return $generique_headers;
		
	}

	/* private function post_api($page, $fields = null)
	{
		$session = curl_init();

		curl_setopt($session, CURLOPT_URL, $this->url_api_im . $page);
		curl_setopt($session, CURLOPT_HTTPHEADER, $this->get_headers($fields));
		curl_setopt($session, CURLOPT_POST, true);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		if ( isset($fields) )
		{
			curl_setopt($session, CURLOPT_POSTFIELDS, json_encode ($fields));
		}
		$json = curl_exec($session);
		curl_close($session);
        return json_decode($json);
	} */

	
	function get_api()
	{
		$url_api  = sprintf("http://%s/api/v1/site/state", $this->beaamIP);
		$curl = curl_init();

		curl_setopt_array($curl, [
		  CURLOPT_URL => $url_api ,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => $this->get_headers(),
		]);

		$response = curl_exec($curl);
		curl_close($curl);
		return json_decode($response);

	}

	/* private function del_api($page)
	{
		$session = curl_init();

		curl_setopt($session, CURLOPT_URL, $this->url_api_im . $page);
		curl_setopt($session, CURLOPT_HTTPHEADER, $this->get_headers());
		curl_setopt($session, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		$json = curl_exec($session);
		curl_close($session);
//		throw new Exception(__('La livebox ne repond pas a la demande de cookie.', __FILE__));
		return json_decode($json);
	}

    function logout()
	{
		$result = $this->del_api("token/".$this->token);
		if ( $result !== false )
		{
			unset($this->token);
			unset($this->provider);
			return true;
		}
		return false;
	}
	
	function list_robots()
	{
		$list_robot = array();
		foreach ($this->get_api("mowers") as $robot)
		{
			$list_robot[$robot->id] = $robot;
		}
		return $list_robot;
	}
	
	function get_robot()
	{
		return $this->get_api("mowers");
	}

	function get_status($mover_id)
	{
		
		return $this->get_api("mowers/".$mover_id."/status");
	}

	function get_geofence($mover_id)
	{
		
		return $this->get_api("mowers/".$mover_id."/geofence");
	}
	
	function control($mover_id, $command)
	{
		switch ($command)
		{
			case 'park' :
			case 'pause':
			case 'start': 					return $this->get_api("mowers/".$mover_id."/control/".$command, array("period" => 180));
			              					break;
			case 'start3h':					return $this->get_api("mowers/".$mover_id."/control/start/override/period", array("period" => 180));
			              					break;
			case 'start6h':					return $this->get_api("mowers/".$mover_id."/control/start/override/period", array("period" => 360));
			              					break;
			case 'start12h':				return $this->get_api("mowers/".$mover_id."/control/start/override/period", array("period" => 720));
			              					break;
			case 'parkuntilnextschedule':	return $this->get_api("mowers/".$mover_id."/control/park/duration/timer", array("period" => 0));
			              					break;
			case 'park3h':					return $this->get_api("mowers/".$mover_id."/control/park/duration/timer", array("period" => 180));
											break;
			case 'park6h':					return $this->get_api("mowers/".$mover_id."/control/park/duration/timer", array("period" => 360));
											break;
			case 'park12h':					return $this->get_api("mowers/".$mover_id."/control/park/duration/timer", array("period" => 720));
		}
	}
	
	function settings($mover_id, $data = null)
	{
		$result = $this->get_api("mowers/".$mover_id."/settings", array("settings" => $data), true);
		if ($result==NULL) $result->status = "OK";
		return $result;
	}
*/
}


?> 
<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('max_execution_time', 0);

class BackupDroplet
{
	/**
     * This class is developed for creating snapshots of Digital Ocean droplets backup.
     * @access Rights : Task Scheduler
     * @param Array : Validation Declaration.
     * @param Constant : Declaration.
     * @param Parameter : Declaration.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
	*/
	private $sms_login_url = 'http://206.189.130.62/sms/api/login';
	private $sms_snapshot_mail_url = 'http://206.189.130.62/sms/api/cloud/snapshot/success/mail';
    private $digital_ocean_url = 'https://api.digitalocean.com/v2/';
    private $token;
	private $dropletId;
	protected $date_bracket = null;
	protected $today = null;
	protected $generatedSnapshotID = null;
	protected $attemptedSnapshotName = null;
	const droplet_active = 1;
	const droplet_inactive = 0;
	const snapshot_created = 2;
	const snapshot_exists = 4;
	const power_on_success = "power_on";
	protected $power_on = [
		"type" => "power_on"
	];
	protected $power_off = [
		"type" => "power_off"
	];
	private $sms_login_credentials = [
		"email" => "sameer.jambhulkar@wwindia.com", 
		"password" => "Sameer1#"
	];

	/**
    	* @Initialization constructor
    */
    public function __construct($token, $dropletId)
    {
        $this->token = $token;
		$this->dropletId = $dropletId;
		$this->today = date('d-M-Y');
	} 	// end : __construct
	
	/**
		* This function is used for turning on the digital ocean server whenever called.
		* @return JSON : Droplet information along with status.
		* @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
	public function powerOn()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->digital_ocean_url . 'droplets/' . $this->dropletId . '/actions');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->token
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->power_on));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        $jsonResponse = curl_exec($ch);
        curl_close($ch);
		$response = json_decode($jsonResponse);
		return $response->action->type;
    } // end : powerOn

	/**
		* This function is used for turning off the digital ocean server whenever called.
		* @return JSON : Droplet information along with status.
		* @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function powerOff()
    {
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->digital_ocean_url . 'droplets/' . $this->dropletId . '/actions');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->token
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->power_off));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    } // end : powerOff

	/**
		* This function is used for checking the status of digital ocean server whenever called.
		* @return CONSTANTS : Status ( TRUE / FALSE)
		* @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function checkIfOn()
    {
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->digital_ocean_url . 'droplets/' . $this->dropletId);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->token
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		$response = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($response);
        if ($result->droplet->status == 'active') {
            return self::droplet_active;
        }
        return self::droplet_inactive;
    } // end : checkIfOn

	/**
		* This function is used for generating snapshots of the Digital Ocean droplets whenever called.
		* @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function createSnapshot()
    {
		$snapshotExists = false;
		$snapshotName = "SMS-" . $this->today;
		$snapshots = $this->getSnapshots();
		foreach ($snapshots as $snapshot) {
			if($snapshot->name == $snapshotName){
				$snapshotExists = true;
			}
		}
		if(!$snapshotExists){
			$data = array("type" => "snapshot", "name" => $snapshotName);
			ini_set('max_execution_time', 0);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->digital_ocean_url . 'droplets/' . $this->dropletId . '/actions');
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Authorization: Bearer ' . $this->token
			));
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
			$snapshotInfo = curl_exec($ch);	
			curl_close($ch);
			$getResponse = json_decode($snapshotInfo);
			do {
				sleep(20);
			} while (($this->retrieveActionInfo($getResponse->action->id)) == 'in-progress');
			$status = self::snapshot_created;
			$snapshotID = $getResponse->action->id;
		}else{
			$status = self::snapshot_exists;
			$snapshotID = '';
		}
		return [
			'status' => $status,
			'snapshotID' => $snapshotID,
			'snapshotName' => $snapshotName
		];
	} // end : createSnapshot

	/**
		* This function is used for retrieval of snapshots from digital ocean droplets whenever called.
		* @return Array : SNAPSHOTS [ If available ]
		* @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
	public function getSnapshots()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->digital_ocean_url . 'droplets/' . $this->dropletId . '/snapshots');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->token
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($response);
        if ($result) {
            return $result->snapshots;
        }
        return [];
	} // end : getSnapshots
	
	/**
		* This function is used for retrieval of snapshots from digital ocean droplets whenever called.
		* @param ACTION-ID : Particular Action ID.
		* @return ACTION-STATUS : [ If available ]
		* @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
	public function retrieveActionInfo($actionID)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->digital_ocean_url . 'actions/' . $actionID);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Bearer ' . $this->token
		));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		$actionResponse = curl_exec($ch);	
		curl_close($ch);
		$result = json_decode($actionResponse);
		return ($result->action->status ? $result->action->status : []);
	}
	
	/**
		* This function is used for performing the Droplet backup procedures
		* @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
	public function backup_procedures()
	{
		try{
			$snapshotExists = false;
			$snapshotName = "SMS-" . $this->today;
			$snapshots = $this->getSnapshots();
			foreach ($snapshots as $snapshot) {
				if($snapshot->name == $snapshotName){
					$snapshotExists = true;
				}
			}
			if(!$snapshotExists)
			{
				switch ($this->checkIfOn()) 
				{
					case self::droplet_active:
						$this->powerOff();
						sleep(10);
						while ($this->checkIfOn())
						{
							sleep(10);
						}
						$create_snapshot = $this->createSnapshot();
						if($create_snapshot['status'] == self::snapshot_created)
						{
							if(($this->powerOn()) == self::power_on_success)
							{
								sleep(30);
								$this->generatedSnapshotID = $create_snapshot['snapshotID'];
								$this->attemptedSnapshotName = $create_snapshot['snapshotName'];
								$this->generate_snapshot_mail_procedures('success');
							}
						}else
						{
							if(($this->powerOn()) == self::power_on_success)
							{
								sleep(30);
								$this->attemptedSnapshotName = $create_snapshot['snapshotName'];
								$this->generate_snapshot_mail_procedures('error');
							}
						}
					break;
				}
			}else
			{
				$this->attemptedSnapshotName = $snapshotName;
				$this->generate_snapshot_mail_procedures('error');
			}
			
		} catch (Exception $e){
			return $e;
		}
	} // end : backup_procedures

	/**
		* This function is used for shooting mail after entire snapshot backup generation procedure.
		* @param Process-Status : success / error
		* @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
	public function generate_snapshot_mail_procedures($process_status)
	{
		$smsToken = $this->sms_login_api();
		$getData = $this->snapshot_mail_api($smsToken,$process_status);
	} // end : generate_snapshot_mail_procedures

	/**
		* This API function is used for generating login token of SMS system.
		* @return Access-Token : Token
		* @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
	private function sms_login_api()
	{
		ini_set('max_execution_time', 0);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->sms_login_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Accept: application/json'
		));
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->sms_login_credentials));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		$loginResponse = curl_exec($ch);	
		curl_close($ch);
		$getResponse = json_decode($loginResponse);
		return $getResponse->data->access_token;
	} // end : sms_login_api

	/**
		* This API function is used for generating mails via SMS project API.
		* @param Access-Token : Token
		* @param Process-Status : success / error
		* @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
	private function snapshot_mail_api($sms_token,$process_status)
	{
		ini_set('max_execution_time', 0);
		if($process_status == 'success')
		{
			$subject = "Digital Ocean droplet snapshot generation: Success.";
			$body = "Snapshot generated successfully.";
			$body.= " Snapshot ID: ".$this->generatedSnapshotID;
			$body.= ". Snapshot Name: ".$this->attemptedSnapshotName;
		}else{
			$subject = "Digital Ocean droplet snapshot generation: Failure.";
			$body = "Snapshot generation failed as snapshot already exists.";
			$body.= " Snapshot Name: ".$this->attemptedSnapshotName;
		}
		$data = [
			"subject" => $subject,
			"body" => $body
		];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->sms_snapshot_mail_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Bearer ' . $sms_token
		));
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		$mailResponseJSON = curl_exec($ch);	
		curl_close($ch);
		$mailResponse = json_decode($mailResponseJSON);
	} // end : snapshot_mail_api
}

$BackupDroplet = new BackupDroplet('aba81709154c3e8b7d083bcbfa06472b23d03d66344a6621cc2acfbff0af033a', '178666658');
$data = $BackupDroplet->backup_procedures();
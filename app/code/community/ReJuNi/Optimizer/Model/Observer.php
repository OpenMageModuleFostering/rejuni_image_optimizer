<?php
class ReJuNi_Optimizer_Model_Observer{
	public function optimizeFilesize(Varien_Event_Observer $observer){
		$enableStatus = Mage::getConfig()->getNode('default/rejuni_optimizer/rejuni_optimizer_group/rejuni_optimizer_enabled');
		if($enableStatus > 0)
		{
			$event = $observer->getEvent();
			$result = $event->getObject();
            
            $newFile = $result->getNewFile();
            Mage::log($newFile, null, 'rejuni.log');
			$this->doApiCall($newFile);
		} else  {
			Mage::log("Optimizer not enabled", null, 'rejuni.log');
		}
	}
	
	private function doApiCall($image) {
		$target_url = Mage::getConfig()->getNode('default/rejuni_optimizer/rejuni_optimizer_group/rejuni_optimizer_apiuri');
		$apikey = Mage::getConfig()->getNode('default/rejuni_optimizer/rejuni_optimizer_group/rejuni_optimizer_apikey');
		
		if(empty($target_url)) { 
            Mage::log("target uri not set", null, 'rejuni.log');
            return;
        }
		if(empty($apikey)) {
            Mage::log("api key not set", null, 'rejuni.log');
            return;
        }
		
		$imageData = getimagesize($image);
		$mimeType  = image_type_to_mime_type($imageData[2]);
		
		//http://php.net/manual/en/class.curlfile.php#115569 see curl_file_create
		$cfile = new CURLFile($image,$mimeType,basename($image)); 
		
		$imgdata = array('uploaded_file' => $cfile);

		$header = array(
			"Authorization: Basic " . base64_encode( $apikey ), 
			"Content-Type: multipart/form-data",
		);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,$target_url);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $imgdata);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

		$result = curl_exec ($ch);
		$status =  curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if( $status !== 200 ) {
			Mage::log("Image mimetype: " . $mimeType, null, 'rejuni.log');
			Mage::log("curl status: " . $status, null, 'rejuni.log');
		} else {
			$writtenBytes = file_put_contents($image, $result);
			if($writtenBytes < 1) Mage::log("zero bytes written", null, 'rejuni.log'); 
		}
	}
}
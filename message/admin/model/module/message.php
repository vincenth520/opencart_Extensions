<?php 
class ModelModuleMessage extends Model {
	
	public function insertMessageText($SenderId,$text)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "messageText SET SenderId = '" . $SenderId . "', MessageText =  '" . $text . "'";
		$this->db->query($sql);
		return $this->db->getLastId();
	}

	public function insertMessage($SenderId,$ReceiverId,$MessageTextId)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "message SET SenderId = '" . $SenderId . "',ReceiverId =  '" . $ReceiverId . "' , MessageTextId =  '" . $MessageTextId . "',SendTime =  '" . date('Y-m-d H:i:s',time()) . "'";
		$this->db->query($sql);
	}

	public function getMessages($page = 1)
	{
		$limit = ($page - 1) * 20;

		$sql = "SELECT msg.id,msg.SenderId,msg.ReceiverId,msg.SendTime,msg.ReadFlag,msgt.messageText FROM " . DB_PREFIX . "message msg INNER JOIN " . DB_PREFIX . "messageText msgt ON msg.MessageTextId=msgt.Id WHERE msg.ReceiverId=0 order by msg.ReceiverId asc,msg.id desc limit " . $limit . ",20";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getMessagestation()
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "message where ReceiverId=0");

		return $query->row['total'];
	}

	public function getUnreadMessagestation()
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "message where ReadFlag = 0 and ReceiverId=0");

		return $query->row['total'];
	}

	public function setMessageStatus($id,$status)
	{
		return $this->db->query("UPDATE ".DB_PREFIX . "message set ReadFlag = ".(int)$status." where id = ".(int)$id);
	}

	public function getMessageItem($SenderId,$ReceiverId)
	{
		$sql = "select msg.id,msg.SenderId,msg.ReceiverId,msg.SendTime,msg.ReadFlag,msgt.MessageText from " . DB_PREFIX . "message msg," . DB_PREFIX . "messageText msgt where msg.MessageTextId = msgt.id and ((msg.SenderId = ". (int)$SenderId ." and msg.ReceiverId = ". (int)$ReceiverId .") or (msg.ReceiverId = ". (int)$SenderId ." and msg.SenderId = ". (int)$ReceiverId ."))";

		$query = $this->db->query($sql);
		foreach ($query->rows as $key => $value) {
			if($value['ReceiverId'] == $ReceiverId){
				$this->setMessageStatus($value['id'],1);
			}
		}
		return $query->rows;
	}
}
?>
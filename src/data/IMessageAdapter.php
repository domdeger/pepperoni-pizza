<?php

interface IMessageAdapter
{
	public function Add($userId, $messageContent);

	public function GetLastMessages ($count, $skip = 0);
	public function GetAllMessagesByUserId ($userId);

	public function Delete ($messageId);
}

?>

<?php

interface IUserAdapter
{
	public function Add($userName, $password, $userPrivileges, $disabled = false);
	public function Enable($userId);

	public function CheckCredentials($userId, $password);

	public function GetAll();
	public function GetIdByName($userName);
	public function GetPrivilegesById($userId);

	public function UpdatePassword($userId, $password);

	public function Disable($userId);
}

?>

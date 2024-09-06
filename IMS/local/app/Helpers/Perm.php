<?php namespace App\Helpers;
use App\User;
use Auth;

class Perm {

    /**
     * Returns an excerpt from a given string (between 0 and passed limit variable).
     *
     * @param $string
     * @param int $limit
     * @param string $suffix
     * @return string
     */
    public static function check($perm)
    {
       //get all permission of logged in user and make it an array
	   $array = explode(",",Auth::user()->perm);
	   
	   //check for permission
	   if(in_array($perm,$array) || in_array("All",$array))
	   {
		   return true;
	   }	   
	   else
	   {
		   return false;
	   }
    }
	
	/*
	@Get added by and updated by
	*/
	public static function addUpdate($addedBy,$updatedBy)
	{
		$getAddedBy 	= User::find($addedBy);
		$getupdatedBy 	= User::find($updatedBy);
		
		if(count($getAddedBy) > 0)
		{
			$addedName = "Added By ".$getAddedBy->person_name;
		}
		else
		{
			$addedName = null;
		}
		
		if(count($getupdatedBy) > 0)
		{
			$UpdatedName = " | Updated By ".$getupdatedBy->person_name;
		}
		else
		{
			$UpdatedName = null;
		}
		
		return $addedName.$UpdatedName;
	}
}
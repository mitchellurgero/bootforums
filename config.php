<?php
/*
Configuration for BootForums

Very simple, read comment, make changes where it's needed. I would recommend not changing captchaLogin as it helps prvent bruteforce attacks, and is simple to type an extra 4-6 chars.
Full branding is supported, and ENCOURAGED, so please find the title, desc, copyright, and footer options and change them =)

Script Created by Mitchell Urgero
Date: Sometime in 2016 ;)
Website: https://urgero.org
E-Mail: info@urgero.org

Script is distributed with Open Source Licenses, do what you want with it. ;)

"I wrote this because I saw that there are not that many databaseless Forums for PHP. It needed to be done. I think it works great, looks good, and is VERY mobile friendly. I just hope at least one other person
finds this PHP script as useful as I do."

*/

$config = array(
	"admins" => array(""), //Username(s) of the account you want to be admin(**Register this account FIRST) This user will have access to locking threads, or deleting them even if they do not own it. Leave as just "" for no admin accounts.
	"title"=>"BootForums", //Title for the forums
	"desc"=>"Open Source Bootstrap themed forum for php 5.6+ - Flat file, no Database required!", //Description for the site in html (Not displayed on page, but in search engines.)
	//Data folders must NOT be the same folders, please follow a similar structure to what I have below. (/path/to/data/users & /path/to/data/threads)
	"user_data" => "/var/forum_data/users", //Folder to store user data in, make sure to give proper permissions (0744) and the owner of the folder must be apache's user (Or nginx's user)
	"thread_data" => "/var/forum_data/threads", //Folder to store thread data in, make sure to give proper permissions (0744) and the owner of the folder must be apache's user (Or nginx's user)
	//Captcha Settings for REGISTRATION page
	"captcha" => array( //Configure Captcha settings for registration.
    					'min_length' => 4,
    					'max_length' => 8,
    					'backgrounds' => array('backgrounds/45-degree-fabric.png','backgrounds/45-degree-fabric.png','backgrounds/45-degree-fabric.png','backgrounds/45-degree-fabric.png'),
    					'fonts' => array('fonts/times_new_yorker.ttf'),
    					'characters' => 'ABCDEFGHJKLMNPRSTUVWXYZabcdefghjkmnprstuvwxyz23456789',
    					'min_font_size' => 15,
    					'max_font_size' => 20,
    					'color' => '#666',
    					'angle_min' => 0,
    					'angle_max' => 20,
    					'shadow' => true,
    					'shadow_color' => '#fff',
    					'shadow_offset_x' => -1,
    					'shadow_offset_y' => 1
					),
	//Captcha settings for LOGIN page
	"captchaLoginForce" => true, //Forces captcha for ALL logins whether they got the password wrong or not.
	"captchaLogin" => array( //Configure Captcha settings for login.
    					'min_length' => 4,
    					'max_length' => 6,
    					'backgrounds' => array('backgrounds/45-degree-fabric.png','backgrounds/45-degree-fabric.png','backgrounds/45-degree-fabric.png','backgrounds/45-degree-fabric.png'),
    					'fonts' => array('fonts/times_new_yorker.ttf'),
    					'characters' => 'ABCDEFGHJKLMNPRSTUVWXYZabcdefghjkmnprstuvwxyz23456789',
    					'min_font_size' => 15,
    					'max_font_size' => 20,
    					'color' => '#666',
    					'angle_min' => 0,
    					'angle_max' => 20,
    					'shadow' => true,
    					'shadow_color' => '#fff',
    					'shadow_offset_x' => -1,
    					'shadow_offset_y' => 1
					),
	"ssl" => true, //Force an SSL connection for Forums
	"announce" => "This forum is currently in beta, but please enjoy your stay!", //Announcement to show on home page. set to "" if you want to disable. Supports HTML if needed.
	"copyright" => "Copyright &copy; URGERO.ORG 2016", //Copyright footer, can be anything, don't be shy. (Copyright is centered in page.)
	"footer" => '<center><a style="color:blue" href="https://github.com/mitchellurgero/bootforums">Get BootForums from GitHub today!</a></center>', //Footer will be displayed under the copyright text, you can put links, or whatever you want in here.
	"registration" => false, //Allow or disallow public registration (Disabled by default, for now, just change to true to enable.)
	"perPage" => 20, //Default amount of threads to show per page. (Index only.)
	"perPageThread" => 10, //Default reply amount to show in each thread (View mode)
	"allowNewThreads" => false, //Enable or disable the New Post button on index page, this will also disable cURL posts!
	"theme" => "cosmo"//Select a theme: orig, cyborg, dark, journal, superhero, readable, or cosmo.
	);
?>
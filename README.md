# cdrplus
CDRPlus - Live call stats &amp; reporting software for Asterisk PBX

Designer: Mark Berry www.MBITServices.com.au  
Coding: Canlan318 @ Freelancer.com

SUMMARY:

A live call logging system, updated and viewed via browser using staf names or teams to show call numbers in a live tally, and as tallied call totals per day/week/month in graphs that can auto rotate in the browser window based on optional settings and can therefore be displayed live on an LCD screen in the office too.
 
CDRPlus was designed to run on Windows 7 usin XAMPP, and accesses a seperate server running Asterisk PBX to monitor the CDR reports database. Using additional db’s tables manually created on the PBX to include and connect extensions, teams and staff names then use that info to match and calculate calls made over various periods. This info is then made available via the CDRPlus software and accessible via browser from the network.

There is also a reporting system built in that can be set to run on a schedule and mail out using external mail server if you have access to one. There was some sendmail settings but not sure if they worked very well.

Updates to databases and records are made via a browser window and reports are also possible that run scheduled times or on demand. This is also updated from the browser.

The main issue with this software is that it has absolutely no security and makes the PBX vulnerable via MYSql access as that port needs to be open. I managed this using very tight firewall control internally on the office network restricting access to the PBX using iptables but it would be easy to hack in its current state. I never had a problem as long as only I had admin access to the CDRPlus and PBX machines. 

CDRPLUS version  2.0 is tested on Windows 7 pro (virtual) connecting to FreePBX and Elastix 2.3 Mysql on a remote linux box on the same LAN subnet.

I ran this live in a business environment with great success for 2 years, firstly on Elastix and then I moved to FreePBX which handled it a lot better and I felt to be a more robust system than Elastix.

I am opening the software up to the open source community so others can benefit from it and start to work on it to improve it. It is a great design but just needs the security side addressing within the software and also turning into something less manually cumbersome to install.

I will not be updating this personally but will try to remain available to anyone in the early stages who wants to take on making it more user friendly in the install stage. There is also the issue of clarity around the database setup that I dont seem to have info on. If you want to get started begin with the Installation Instructions as everything you need should be in there to get started. 

I include the PBX builds instructions as well as I feel they may help people with problems I ran into when first designing these to work together. 

Rgds
Mark – August 2016.

FOLDER CONTENTS:  
- Asterisk PBX Build Info  
	This is the Golden Standard PBX build I was working to and may help if anyone runs into PBX build problems. 		I began using Elastix but moved to FreePBX which was better and more stable.
- CDRPlus_Software_Vrs2  
	The software itself in archive form and unpacked into \cdr folder 
	Installation Instructions
- Database_Stuff  
	Legacy info that may be of use. Mostly ignore it as it probably is not in the current build.
- Design_Specs  
	Some of the design specs I worked with Canlan318 to get vrs 1 and vrs 2 completed. Any probloems we ran into 		would not be reflected here so bear that in mind. It may not be exactly how vrs 2 ended up.
- Early_CDRPlus_Builds    
	again this is for reference and has a lot of the early workings and screenshots. it may help any coder 		wanting to understand better how it works and the intent behind it. prbably not needed but I put it here 	anyway.
- Program_Screenshots    
	screen shots of the browser, there are more in the Early Builds folder.
- To Do List  
	just my to do list as I left it the last time I looked at updating the software.



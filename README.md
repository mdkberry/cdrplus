# cdrplus
CDRPlus - Live call stats &amp; reporting software for Asterisk PBX

Designer: Mark Berry www.MBITServices.com.au  
Coding: Canlan318 @ Freelancer.com

SUMMARY:

A live call logging system, updated and viewed via browser using teams to show call numbers in a live tally, or totals per day/week/month in a chart that can auto rotate based on settings and be displayed live on a screen in the office.
 
CDRPlus was designed to run on Windows 7 usin XAMPP, and accesses a seperate server running Asterisk PBX to monitor the CDR reports database. Using additional db’s manually created on the PBX to include and connect extensions, teams and staff names. This info is then run live via the CDRPlus software and accessible via browser from the network.

Updates to db are made via a browser window and reports are also possible that run scheduled times or on demand. This is also updated from the browser.

The main issue with this software is that it has absolutely no security and makes the PBX vulnerable via MYSql access as that port needs to be open. I managed this using very tight firewall control internally on the office network. I never had a problem as long as only I had admin access to the CDRPlus and PBX. 

CDRPLUS version  2.0 is tested on Windows 7 pro connecting to Elastix 2.3 Mysql on a remote linux box on the same LAN subnet.

I ran this live in a business environment with great success for 2 years, firstly on Elastix and then I moved to FreePBX which handled it a lot better and I felt to be a more robust system than Elastix.

I am opening the software up to the open source community so others can benefit from it and start to work on it to improve it. It is a great design but just needs the security side addressing within the software and also turning into something less manually cumbersome to install.

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

KNOWN ISSUES:

Sadly there is no working PBX with CDRPlus I have access to so I have lost info on the database design. I have gone into more details about this in Installation Instructions and any coder should be able to work it out from db.php 

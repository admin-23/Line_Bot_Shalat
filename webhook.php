botshalat23.herokuapp.com/bot.php

from Public import *
from humanfriendly import format_timespan
from time import sleep
import time, sys, re, ast, atexit, os, random
    
#Login Method#
#client = LINE()
client = LINE("EE80XFpmJbqCj8aFhND3.5+RdP8az4On/kdOQN5zQOW.YfxtK8qGLsW/aCyUJEb9A2W6YESTHFykw8K2IyVag3k=")
clientMid = client.profile.mid
clientProfile = client.getProfile()
clientSettings = client.getSettings()
clientPoll = OEPoll(client)
botStart = time.time()

assist = LINE("u18385ed66431300056b441c5e6561a43:aWF0OiAxNTU0NTg4NzYxMTY4Cg==..EnMX9H/HUjc2dVIpa3/BYectzbM=", appName="IOS\t8.19.1\tEater\t1")
assistMid = assist.profile.mid

assist2 = LINE("u5499dcf8a13a008c9a6d0bb4f9a8bc80:aWF0OiAxNTU0NTg4NzA1NDkxCg==..ZYOtjiR0praQ+F2EilM4CB/4geM=", appName="IOS\t8.19.1\tEater\t1")
assist2Mid = assist2.profile.mid

assist3 = LINE("u65b0b17f41fcdf70a9672b2feb4c317c:aWF0OiAxNTU0NTg4MTQ1MTM0Cg==..K9eMqpL0M8pGjlqiMj/I8A5pTyk=", appName="IOS\t8.19.1\tEater\t1")
assist3Mid = assist3.profile.mid

#List Bot #
Admin = ["u32ab94079d4844d7b3b1a1f225aee0c5"]
dolphinBot = [clientMid, assistMid, assist2Mid, assist3Mid]
dolphinJoin = [assist, assist2, assist3]
dolphinMessage = [client, assist, assist2, assist3]

msg_dict = {}

settings = {
    "protectKick": {
        "on": False,
        "list": [],
    },
    "protectInvite": {
        "on": False,
        "list": [],
    },
    "protectCancel": {
        "on": False,
        "list": [],
    },
    "protectQr": {
        "on": False,
        "list": [],
    },
    "staff": {
        "add": False,
        "delete": False,
        "list": [],
    },
    "blacklist": {
        "add": False,
        "delete": False,
        "list": [],
    },
    "setKey": False,
    "keyCommand": "",
    "autoAdd": True,
    "autoJoin": True,
}

def restartBot():
	print ("[ INFO ] BOT RESTART")
	python = sys.executable
	os.execl(python, python, *sys.argv)
                        
def sendMention(to, text="", mids=[]):
    arrData = ""
    arr = []
    mention = "@DolphinHmm"
    if mids == []:
        raise Exception("Invalid mids")
    if "@!" in text:
        if text.count("@!") != len(mids):
            raise Exception("Invalid mids")
        texts = text.split("@!")
        textx = ""
        for mid in mids:
            textx += str(texts[mids.index(mid)])
            slen = len(textx)
            elen = len(textx) + 15
            arrData = {'S':str(slen), 'E':str(elen - 4), 'M':mid}
            arr.append(arrData)
            textx += mention
        textx += str(texts[len(mids)])
    else:
        textx = ""
        slen = len(textx)
        elen = len(textx) + 15
        arrData = {'S':str(slen), 'E':str(elen - 4), 'M':mids[0]}
        arr.append(arrData)
        textx += mention + str(text)
    client.sendMessage(to, textx, {'AGENT_NAME':'「 Admin -23 」', 'AGENT_LINK': 'line://ti/p/~{}'.format(client.getProfile().userid), 'AGENT_ICON': "http://dl.profile.line-cdn.net/" + client.getProfile().picturePath, 'MENTION': str('{"MENTIONEES":' + json.dumps(arr) + '}')}, 0)

def command(text):
    pesan = text.lower()
    if settings["setKey"] == True:
        if pesan.startswith(settings["keyCommand"]):
            cmd = pesan.replace(settings["keyCommand"],"")
        else:
            cmd = "Undefined command"
    else:
        cmd = text.lower()
    return cmd			

def helpmessage():
    if settings['setKey'] == True:
        key = settings['keyCommand']
    else:
        key = ''
    helpMessage =   "[ Help Message ]" + "\n" + \
                     "[•] " + key + "Help" + "\n" + \
                     "[•] " + key + "Speed" + "\n" + \
                     "[•] " + key  + "Restart" + "\n" + \
                     "[•] " + key + "Runtime" + "\n" + \
                     "[•] " + key + "Respon [ Text ]" + "\n" + \
                     "[•] " + key + "Status" + "\n" + \
                     "[•] " + key + "ProtectKick [ On/Off ]" + "\n" + \
                     "[•] " + key + "ProtectInvite [ On/Off ]" + "\n" + \
                     "[•] " + key + "ProtectCancel [ On/Off ]" + "\n" + \
                     "[•] " + key + "ProtectQr [ On/Off ]" + "\n" + \
                     "[•] " + key + "Bot Join" + "\n" + \
                     "[•] " + key + "Bot Bye" + "\n" + \
                     "[•] " + key + "Clear Blacklist" + "\n" + \
                     "[•] " + key + "Contact [ Mention ]" + "\n" + \
                     "[•] " + key + "StaffAdd/Delete [ Mention ]" + "\n" + \
                     "[•] " + key + "BlacklistAdd/Delete [ Mention ]" + "\n" + \
                     "[•] " + key + "Staff [ Add/Delete ]" + "\n" + \
                     "[•] " + key + "Blacklist [ Add/Delete ]" + "\n" + \
                     "[ About ]" + "\n" + \
                     "[•] Simple Protect [•]" + "\n" + \
                     "[•] Creator : Dolphin [•]" + "\n" \
                     "[ line.me/ti/p/~dellacgua ]"
    return helpMessage

def blacklist(target):
	if target not in settings["blacklist"]["list"]:
		settings["blacklist"]["list"].append(target)

def clientBot(op):
    try:
        if op.type == 0:
            return
#Add Contact#
        if op.type == 5:
        	if settings["autoAdd"] == True:
        		if op.param2 in Admin:
        			client.findAndAddContactsByMid(op.param1)
        			for contact in dolphinBot:
        				client.sendContact(op.param1, contact)
        			client.sendMessage(op.param1, "This Is Your Bot\nPlease Add")
        		else:
        			client.blockContact(op.param1)
        			client.sendMessage(op.param1, "You Are Not My Admin\nBlocked Done")
        		if op.param2 in settings["staff"]["list"]:
        			for add in dolphinJoin:
        				add.findAndAddContactsByMid(op.param1)
        				add.sendMessage(op.param1, "Hello Staff\nline.me/ti/p/~dellacgua\nThis Is My Author ^_^")
        		else:
        			client.blockContact(op.param1)
        			client.sendMessage(op.param1, "You Are Not My Staff\nBlocked Done")
#Protect Qr#
        if op.type == 11:
        	if settings["protectQr"]["on"] == True:
        		g = client.getGroup(op.param1)
        		g.preventedJoinByTicket = True
        	if op.param1 in settings["protectQr"]["list"]:
        		if op.param2 not in dolphinBot and op.param2 not in settings["staff"]["list"] and op.param2 not in Admin:
        			try:
        				assist.updateGroup(g)
        				assist.kickoutFromGroup(op.param1, [op.param2])
        				blacklist(op.param2)
        				assist.sendMessage(op.param1, "Qr Detected :/")
        			except:
        				try:
        					assist2.updateGroup(g)
        					assist2.kickoutFromGroup(op.param1, [op.param2])
        					blacklist(op.param2)
        					assist2.sendMessage(op.param1, "Qr Detected :/")
        				except:
        					try:
        						assist3.updateGroup(g)
        						assist3.kickoutFromGroup(op.param1, [op.param2])
        						blacklist(op.param2)
        						assist3.sendMessage(op.param1, "Qr Detected :/")
        					except Exception as e:
        						assist.sendMessage(op.param1, str(e))
#Protect Invite#
        if op.type == 13:
        	if settings["protectInvite"]["on"] == True:
        		g = client.getGroup(op.param1)
        	if op.param1 in settings["protectInvite"]["list"]:
        		if op.param2 not in dolphinBot and op.param2 not in settings["staff"]["list"] and op.param2 not in Admin:
        			try:
        				assist.cancelGroupInvitation(op.param1, [op.param3])
        				assist.kickoutFromGroup(op.param1, [op.param2])
        				blacklist(op.param2)
        				assist.sendMessage(op.param1, "Invite Detect :/")
        			except:
        				try:
        					assist2.cancelGroupInvitation(op.param1, [op.param3])
        					assist2.kickoutFromGroup(op.param1, [op.param2])
        					blacklist(op.param2)
        					assist2.sendMessage(op.param1, "Invite Detect :/")
        				except:
        					try:
        						assist3.cancelGroupInvitation(op.param1, [op.param3])
        						assist3.kickoutFromGroup(op.param1, [op.param2])
        						blacklist(op.param2)
        						assist3.sendMessage(op.param1, "Invite Detect :/")
        					except Exception as e:
        						assist.sendMessage(op.param1, str(e))
        	if settings["autoJoin"] == True:
        		if clientMid in op.param3:
        			if op.param2 in settings["staff"]["list"] and op.param in Admin:
        				client.acceptGroupInvitation(op.param1)
        			else:
        				client.acceptGroupInvitation(op.param1)
        				client.sendMessage(op.param1, "Sorry ^_^\nPlease Contact My Admin")
        				client.sendContact(op.param1, Admin)
        				client.leaveGroup(op.param1)
#Protect Join Blacklist#
        if op.type == 17:
        	if op.param2 in settings["blacklist"]["list"]:
        		try:
        			assist.kickoutFromGroup(op.param1, [op.param2])
        			assist.sendMessage(op.param1, "Blacklist Detected :/")
        		except:
        			try:
        				assist2.kickoutFromGroup(op.param1, [op.param2])
        				assist2.sendMessage(op.param1, "Blacklist Detected :/")
        			except:
        				try:
        					assist3.kickoutFromGroup(op.param1, [op.param2])
        					assist3.sendMessage(op.param1, "Blacklist Detected :/")
        				except:
        					pass
#Protect Kick#
        if op.type == 19:
        	if settings["protectKick"]["on"] == True:
        		g = client.getGroup(op.param1)
        	if op.param1 in settings["protectKick"]["list"]:
        		if op.param2 not in dolphinBot and op.param2 not in settings["staff"]["list"] not in Admin:
        			try:
        				assist.kickoutFromGroup(op.param1, [op.param2])
        				blacklist(op.param2)
        			except:
        				try:
        					assist2.kickoutFromGroup(op.param1, [op.param2])
        					blacklist(op.param2)
        				except:
        					try:
        						assist3.kickoutFromGroup(op.param1, [op.param2])
        						blacklist(op.param2)
        					except Exception as e:
        						assist.sendMessage(op.param1, str(e))
#Client#
        if op.type == 19:
        	if op.param3 in clientMid:
        		if op.param2 not in dolphinBot and op.param2 not in settings["staff"]["list"] and op.param2 not in Admin:
        			assist.kickoutFromGroup(op.param1, [op.param2])
        			blacklist(op.param2)
        			g = assist.getGroup(op.param1)
        			g.preventedJoinByTicket = False
        			assist.updateGroup(g)
        			Ticket = assist.reissueGroupTicket(op.param1)
        			client.acceptGroupInvitationByTicket(op.param1, Ticket)
        			d = assist.getGroup(op.param1)
        			d.preventedJoinByTicket = True
        			assist.updateGroup(d)
        		else:
        			g = assist2.getGroup(op.param1)
        			g.preventedJoinByTicket = False
        			assist2.updateGroup(g)
        			Ticket = assist.reissueGroupTicket(op.param1)
        			client.acceptGroupInvitationByTicket(op.param1, Ticket)
        			d = assist2.getGroup(op.param1)
        			d.preventedJoinByTicket = True
        			assist2.updateGroup(d)
#Assist#
        if op.type == 19:
        	if op.param3 in assistMid:
        		if op.param2 not in dolphinBot and op.param2 not in settings["staff"]["list"] and op.param2 not in Admin:
        			assist2.kickoutFromGroup(op.param1, [op.param2])
        			blacklist(op.param2)
        			g = assist2.getGroup(op.param1)
        			g.preventedJoinByTicket = False
        			assist2.updateGroup(g)
        			Ticket = assist2.reissueGroupTicket(op.param1)
        			assist.acceptGroupInvitationByTicket(op.param1, Ticket)
        			d = assist2.getGroup(op.param1)
        			d.preventedJoinByTicket = True
        			assist2.updateGroup(d)
        		else:
        			g = assist3.getGroup(op.param1)
        			g.preventedJoinByTicket = False
        			assist3.updateGroup(g)
        			Ticket = assist3.reissueGroupTicket(op.param1)
        			assist.acceptGroupInvitationByTicket(op.param1, Ticket)
        			d = assist3.getGroup(op.param1)
        			d.preventedJoinByTicket = True
        			assist3.updateGroup(d)
#Assist 2#
        if op.type == 19:
        	if op.param3 in assist2Mid:
        		if op.param2 not in dolphinBot and op.param2 not in settings["staff"]["list"] and op.param2 not in Admin:
        			assist3.kickoutFromGroup(op.param1, [op.param2])
        			blacklist(op.param2)
        			g = assist3.getGroup(op.param1)
        			g.preventedJoinByTicket = False
        			assist3.updateGroup(g)
        			Ticket = assist3.reissueGroupTicket(op.param1)
        			assist2.acceptGroupInvitationByTicket(op.param1, Ticket)
        			d = assist3.getGroup(op.param1)
        			d.preventedJoinByTicket = True
        			assist3.updateGroup(d)
        		else:
        			g = client.getGroup(op.param1)
        			g.preventedJoinByTicket = False
        			client.updateGroup(g)
        			Ticket = client.reissueGroupTicket(op.param1)
        			assist2.acceptGroupInvitationByTicket(op.param1, Ticket)
        			d = client.getGroup(op.param1)
        			d.preventedJoinByTicket = True
        			client.updateGroup(d)
#Assist 3#
        if op.type == 19:
        	if op.param3 in assist3Mid:
        		if op.param2 not in dolphinBot and op.param2 not in settings["staff"]["list"] and op.param2 not in Admin:
        			client.kickoutFromGroup(op.param1, [op.param2])
        			blacklist(op.param2)
        			g = client.getGroup(op.param1)
        			g.preventedJoinByTicket = False
        			client.updateGroup(g)
        			Ticket = client.reissueGroupTicket(op.param1)
        			assist3.acceptGroupInvitationByTicket(op.param1, Ticket)
        			d = client.getGroup(op.param1)
        			d.preventedJoinByTicket = True
        			client.updateGroup(d)
        		else:
        			g = assist.getGroup(op.param1)
        			g.preventedJoinByTicket = False
        			assist.updateGroup(g)
        			Ticket = assist.reissueGroupTicket(op.param1)
        			assist3.acceptGroupInvitationByTicket(op.param1, Ticket)
        			d = assist.getGroup(op.param1)
        			d.preventedJoinByTicket = True
        			assist.updateGroup(d)
#Invite Staff And Admin#
        if op.type == 19:
        	if op.param2 in Admin:
        		try:
        			assist.findAndAddContactsByMid(op.param2)
        			assist.kickoutFromGroup(op.param1, [op.param2])
        			assist.inviteIntoGroup(op.param1, [Admin])
        		except:
        			try:
        				assist2.findAndAddContactsByMid(op.param2)
        				assist2.kickoutFromGroup(op.param1, [op.param2])
        				assist2.inviteIntoGroup(op.param1, [Admin])
        			except:
        				try:
        					assist3.findAndAddContactsByMid(op.param2)
        					assist3.kickoutFromGroup(op.param1, [op.param2])
        					assist3.inviteIntoGroup(op.param1, [Admin])
        				except:
        					pass
        	if op.param2 in settings["staff"]["list"]:
        		try:
        			assist.findAndAddContactsByMid(op.param2)
        			assist.kickoutFromGroup(op.param1, [op.param2])
        			blacklist(op.param2)
        			assist.inviteIntoGroup(op.param1, [op.param2])
        		except:
        			try:
        				assist2.findAndAddContactsByMid(op.param2)
        				assist2.kickoutFromGroup(op.param1, [op.param2])
        				blacklist(op.param2)
        				assist2.inviteIntoGroup(op.param1, [op.param2])
        			except:
        				try:
        					assist3.findAndAddContactsByMid(op.param2)
        					assist3.kickoutFromGroup(op.param1, [op.param2])
        					blacklist(op.param2)
        					assist3.inviteIntoGroup(op.param1, [op.param2])
        				except:
        					pass
#Protect Cancel#
        if op.type == 32:
        	if settings["protectCancel"]["on"]:
        		g = client.getGroup(op.param1)
        	if op.param1 in settings["protectCancel"]["list"]:
        		if op.param2 not in dolphinBot and op.param2 not in settings["staff"]["list"] and op.param2 not in Admin:
        			try:
        				assist.kickoutFromGroup(op.param1, [op.param2])
        				blacklist(op.param2)
        				assist.sendMessage(op.param1, "Cancel Detected :/")
        			except:
        				try:
        					assist2.kickoutFromGroup(op.param1, [op.param2])
        					blacklist(op.param2)
        					assist2.sendMessage(op.param1, "Cancel Detected :/")
        				except:
        					try:
        						assist3.kickoutFromGroup(op.param1, [op.param2])
        						blacklist(op.param2)
        						assist3.sendMessage(op.param1, "Cancel Detected :/")
        					except Exception as e:
        						assist.sendMessage(op.param1, str(e))
#Start Command#
        if op.type == 26:
        	msg = op.message
        	text = msg.text
        	msg_id = msg.id
        	receiver = msg.to
        	sender = msg._from
        	setKey = settings["keyCommand"].title()
        	if settings["setKey"] == False:
        		setKey = ''
        	if msg.toType == 0 or msg.toType == 1 or msg.toType == 2:
        		if msg.toType == 0:
        			if sender != client.profile.mid:
        				to = sender
        			else:
        				to = receiver
        		elif msg.toType == 1:
        			to = receiver
        		elif msg.toType == 2:
        			to = receiver
        		if msg.contentType == 0:
        			if text is None:
        				return
        			else:
        				cmd = command(text)
        				if cmd == "help":
        					helpMessage = helpmessage()
        					client.sendMessage(to, str(helpMessage))
        				elif cmd == "restart":
        					if msg._from in Admin:
        						client.sendMessage(to, "Restart Bot Succes")
        						restartBot()
        					else:
        						client.sendMessage(to, "You Are Not My Admin")
        				elif cmd == "speed":
        					start = time.time()
        					client.sendMessage(to, "Loading Speed...")
        					elapse = time.time() - start
        					client.sendMessage(to, "{} Seconds".format(str(elapse)))
        				elif cmd == "runtime":
        					runtime = time.time() - botStart
        					runtime = format_timespan(runtime)
        					client.sendMessage(to, "{}".format(str(runtime)))
        				elif cmd.startswith("respon "):
        					if msg._from in Admin:
        						sep = text.split(" ")
        						res = text.replace(sep[0] + " ", "")
        						for message in dolphinMessage:
        							message.sendMessage(to, str(res))
        					else:
        						if msg._from in settings["staff"]["list"]:
        							sep = text.split(" ")
        							res = text.replace(sep[0] + " ", "")
        							for message in dolphinMessage:
        								message.sendMessage(to, str(res))
        						else:
        							client.sendMessage(to, "You Are Not My Staff")
        				elif cmd == "status":
        					if msg._from in Admin:
        						groups = client.getGroupIdsJoined()
        						ret_ = "[ Status ]"
        						num = 0
        						for group in groups:
        							if group in settings["protectKick"]["list"]:
        								g = client.getGroup(group)
        								num += 1
        								ret_ += "\n[ {} ] Group : {}".format(str(num), str(g.name))
        								ret_ += "\n[•] Protect Kick [ On ]"
        							else: ret_ += "\n[•] Protect Kick [ Off ]"
        							if group in settings["protectInvite"]["list"]:
        								ret_ += "\n[•] Protect Invite [ On ]"
        							else: ret_ += "\n[•] Protect Invite [ Off ]"
        							if group in settings["protectCancel"]["list"]:
        								ret_ += "\n[•] Protect Cancel [ On ]"
        							else: ret_ += "\n[•] Protect Cancel [ Off ]"
        							if group in settings["protectQr"]["list"]:
        								ret_ += "\n[•] Protect Qr [ On ]"
        							else: ret_ += "\n[•] Protect Qr [ Off ]"
        						ret_ += "\n[ Finish ]"
        						client.sendMessage(to, str(ret_))
        					else:
        						if msg._from in settings["staff"]["list"]:
        							groups = client.getGroupIdsJoined()
        							ret_ = "[ Status ]"
        							num = 0
        							for group in groups:
        								if group in settings["protectKick"]["list"]:
        									g = client.getGroup(group)
        									num += 1
        									ret_ += "\n[ {} ] Group : {}".format(str(num), str(g.name))
        									ret_ += "\n[•] Protect Kick [ On ]"
        								else: ret_ += "\n[•] Protect Kick [ Off ]"
        								if group in settings["protectInvite"]["list"]:
        									ret_ += "\n[•] Protect Invite [ On ]"
        								else: ret_ += "\n[•] Protect Invite [ Off ]"
        								if group in settings["protectCancel"]["list"]:
        									ret_ += "\n[•] Protect Cancel [ On ]"
        								else: ret_ += "\n[•] Protect Cancel [ Off ]"
        								if group in settings["protectQr"]["list"]:
        									ret_ += "\n[•] Protect Qr [ On ]"
        								else: ret_ += "\n[•] Protect Qr [ Off ]"
        							ret_ += "\n[ Finish ]"
        							client.sendMessage(to, str(ret_))
        				elif cmd == "protectkick on":
        					if msg._from in Admin:
        						g = client.getGroup(to)
        						if to in settings["protectKick"]["list"]:
        							client.sendMessage(to, "Protect Kick Is Online")
        						else:
        							settings["protectKick"]["on"] = True
        							settings["protectKick"]["list"].append(to)
        							client.sendMessage(to, "Protect Kick Online")
        					else:
        						client.sendMessage(to, "You Are Not My Admin")
        						if msg._from in settings["staff"]["list"]:
        							g = client.getGroup(to)
        							if to in settings["protectKick"]["list"]:
        								client.sendMessage(to, "Protect Kick Is Online")
        							else:
        								settings["protectKick"]["on"] = True
        								settings["protectKick"]["list"].append(to)
        								client.sendMessage(to, "Protect Kick Online")
        						else:
        							client.sendMessage(to, "You Are Not My Staff")
        				elif cmd == "protectkick off":
        					if msg._from in Admin:
        						if to not in settings["protectKick"]["list"]:
        							client.sendMessage(to, "Protect Kick Is Offline")
        						else:
        							settings["protectKick"]["on"] = False
        							settings["protectKick"]["list"].remove(to)
        							client.sendMessage(to, "Protect Kick Offline")
        					else:
        						client.sendMessage(to, "You Are Not My Admin")
        						if msg._from in settings["staff"]["list"]:
        							if to in settings["protectKick"]["list"]:
        								client.sendMessage(to, "Protect Kick Is Offline")
        							else:
        								settings["protectKick"]["on"] = False
        								settings["protectKick"]["list"].remove(to)
        								client.sendMessage(to, "Protect Kick Offline")
        						else:
        							client.sendMessage(to, "You Are Not My Staff")
        				elif cmd == "protectinvite on":
        					if msg._from in Admin:
        						g = client.getGroup(to)
        						if to in settings["protectInvite"]["list"]:
        							client.sendMessage(to, "Protect Invite Is Online")
        						else:
        							settings["protectInvite"]["on"] = True
        							settings["protectInvite"]["list"].append(to)
        							client.sendMessage(to, "Protect Invite Online")
        					else:
        						client.sendMessage(to, "You Are Not My Admin")
        						if msg._from in settings["staff"]["list"]:
        							g = client.getGroup(to)
        							if to in settings["protectInvite"]["list"]:
        								client.sendMessage(to, "Protect Invite Is Online")
        							else:
        								settings["protectInvite"]["on"] = True
        								settings["protectInvite"]["list"].append(to)
        								client.sendMessage(to, "Protect Invite Online")
        						else:
        							client.sendMessage(to, "You Are Not My Staff")
        				elif cmd == "protectinvite off":
        					if msg._from in Admin:
        						if to not in settings["protectInvite"]["list"]:
        							client.sendMessage(to, "Protect Invite Is Offline")
        						else:
        							settings["protectInvite"]["on"] = False
        							settings["protectInvite"]["list"].remove(to)
        							client.sendMessage(to, "Protect Invite Offline")
        					else:
        						client.sendMessage(to, "You Are Not My Admin")
        						if msg._from in settings["staff"]["list"]:
        							if to not in settings["protectInvite"]["list"]:
        								client.sendMessage(to, "Protect Invite Is Offline")
        							else:
        								settings["protectInvite"]["on"] = False
        								settings["protectInvite"]["list"].remove(to)
        							client.sendMessage(to, "Protect Invite Offline")
        				elif cmd == "protectcancel on" and sender == settings["staff"]["list"] and sender == Admin:
        					if to in settings["protectCancel"]["list"]:
        						client.sendMessage(to, "Protect Cancel Is Online")
        					else:
        						settings["protectCancel"]["on"] = True
        						settings["protectCancel"]["list"].append(to)
        						client.sendMessage(to, "Protect Cancel Online")
        				elif cmd == "protectcancel off" and sender == settings["staff"]["list"] and sender == Admin:
        					if to in settings["protectCancel"]["list"]:
        						client.sendMessage(to, "Protect Cancel Is Offline")
        					else:
        						settings["protectCancel"]["on"] = False
        						settings["protectCancel"]["list"].remove(to)
        						client.sendMessage(to, "Protect Cancel Offline")
        				elif cmd == "protectqr on" and sender == settings["staff"]["list"] and sender == Admin:
        					if to in settings["protectqr"]["list"]:
        						client.sendMessage(to, "Protect Qr Is Online")
        					else:
        						settings["protectQr"]["on"] = True
        						settings["protectQr"]["list"].append(to)
        						client.sendMessage(to, "Protect Qr Online")
        				elif cmd == "protectqr off" and sender == settings["staff"]["list"] and sender == Admin:
        					if to in settings["protectQr"]["list"]:
        						client.sendMessage(to, "Protect Qr Is Offline")
        					else:
        						settings["protectQr"]["on"] = False
        						settings["protectQr"]["list"].remove(to)
        						client.sendMessage(to, "Protect Qr Offline")
        				elif cmd == "bot join" and sender == settings["staff"]["list"] and sender == Admin:
        					g = client.getGroup(to)
        					g.preventedJoinByTicket = False
        					client.updateGroup(g)
        					Ticket = client.reissueGroupTicket(to)
        					for join in dolphinJoin:
        						if join in g.members:
        							client.sendMessage(to, "Your Bot Already Join")
        						else:
        							join.acceptGroupInvitationByTicket(to, Ticket)
        							join.sendMessage(to, "Ready Protect Group")
        							g.preventedJoinByTicket = True
        							client.updateGroup(g)
        				elif cmd == "bot bye" and sender == settings["staff"]["list"] and sender == Admin:
        					g = client.getGroup(to)
        					for bye in dolphinJoin:
        						if bye in g.members:
        							client.sendMessage(to, "Your Bot Already Leave")
        						else:
        							bye.sendMessage(to, "Protect Is Finish")
        							bye.leaveGroup(to)
        				elif cmd == "clear blacklist" and sender == settings["staff"]["list"] and sender == Admin:
        					if to in settings["blacklist"]["list"]:
        						client.sendMessage(to, "Nothing Blacklist")
        					else:
        						settings["blacklist"]["list"] = []
        						client.sendMessage(to, "Succes Clear All Blacklist")
        				elif cmd.startswith("contact"):
        					if 'MENTION' in msg.contentMetadata.keys()!= None:
        						names = re.findall(r'@(\w+)', text)
        						mention = ast.literal_eval(msg.contentMetadata['MENTION'])
        						mentionees = mention['MENTIONEES']
        						lists = []
        						for mention in mentionees:
        							if mention["M"] not in lists:
        								lists.append(mention["M"])
        								for ls in lists:
        									client.sendContact(to, ls)
        				elif cmd.startswith("staffadd"):
        					if msg._from in Admin:
        						if 'MENTION' in msg.contentMetadata.keys()!= None:
        							names = re.findall(r'@(\w+)', text)
        							mention = ast.literal_eval(msg.contentMetadata['MENTION'])
        							mentionees = mention['MENTIONEES']
        							lists = []
        							for mention in mentionees:
        								if mention["M"] not in lists:
        									lists.append(mention["M"])
        									for ls in lists:
        										g = client.getContact(ls)
        										if g.mid in settings["staff"]["list"]:
        											client.sendMessage(to, "Contact Succes Add To Staff")
        										else:
        											settings["staff"]["list"].append(g.mid)
        											client.sendMessage(to, "Succes Add To Staff")
        				elif cmd.startswith("staffdelete") and sender == Admin:
        					if 'MENTION' in msg.contentMetadata.keys()!= None:
        						names = re.findall(r'@(\w+)', text)
        						mention = ast.literal_eval(msg.contentMetadata['MENTION'])
        						mentionees = mention['MENTIONEES']
        						lists = []
        						for mention in mentionees:
        							if mention["M"] not in lists:
        								lists.append(mention["M"])
        								for ls in lists:
        									try:
        										g = client.getContact(ls)
        									except:
        										continue
        									if g.mid not in settings["staff"]["list"]:
        										client.sendMessage(to, "Contact Succes Delete To Staff")
        										continue
        									if g.mid in settings["staff"]["list"]:
        										settings["staff"]["list"].remove(g.mid)
        										client.sendMessage(to, "Succes Delete To Staff")
        				elif cmd.startswith("blacklistadd") and sender == settings["staff"]["list"] and sender == Admin:
        					if 'MENTION' in msg.contentMetadata.keys()!= None:
        						names = re.findall(r'@(\w+)', text)
        						mention = ast.literal_eval(msg.contentMetadata['MENTION'])
        						mentionees = mention['MENTIONEES']
        						lists = []
        						for mention in mentionees:
        							if mention["M"] not in lists:
        								lists.append(mention["M"])
        								for ls in lists:
        									try:
        										g = client.getContact(ls)
        									except:
        										continue
        									if g.mid in settings["blacklist"]["list"]:
        										client.sendMessage(to, "Contact Succes Add To Blacklist")
        										continue
        									if g.mid not in settings["blacklist"]["list"]:
        										settings["blacklist"]["list"].append(g.mid)
        										client.sendMessage(to, "Succes Add To Blacklist")
        				elif cmd.startswith("blacklistdelete") and sender == settings["staff"]["list"] and sender == Admin:
        					if 'MENTION' in msg.contentMetadata.keys()!= None:
        						names = re.findall(r'@(\w+)', text)
        						mention = ast.literal_eval(msg.contentMetadata['MENTION'])
        						mentionees = mention['MENTIONEES']
        						lists = []
        						for mention in mentionees:
        							if mention["M"] not in lists:
        								lists.append(mention["M"])
        								for ls in lists:
        									try:
        										g = client.getContact(ls)
        									except:
        										continue
        									if g.mid not in settings["blacklist"]["list"]:
        										client.sendMessage(to, "Contact Succes Delete To Blacklist")
        										continue
        									if g.mid in settings["blacklist"]["list"]:
        										settings["blacklist"]["list"].remove(g.mid)
        										client.sendMessage(to, "Succes Delete To Blacklist")
        				elif cmd == "staff add":
        					if msg._from in Admin:
        						settings["staff"]["add"] = True
        						client.sendMessage(to, "Please Send Contact If You Want Add To Staff")
        					else:
        						client.sendMessage(to, "You Are Not My Admin")
        				elif cmd == "staff delete" and sender == Admin:
        					settings["staff"]["delete"] = True
        					client.sendMessage(to, "Please Send Contact If You Want Delete To Staff")
        				elif cmd == "blacklist add" and sender == settings["staff"]["list"] and sender == Admin:
        					settings["blacklist"]["add"] = True
        					client.sendMessage(to, "Please Send Contact If You Want Add To Blacklist")
        				elif cmd == "blacklist delete" and sender == settings["staff"]["list"] and sender == Admin:
        					settings["blacklist"]["delete"] = True
        					client.sendMessage(to, "Please Send Contact If You Want Delete To Blacklist")
        		elif msg.contentType == 13:
        			if settings["staff"]["add"] == True:
        				if msg.contentMetadata["mid"] not in settings["staff"]["list"]:
        					mid = msg.contentMetadata["mid"]
        					settings["staff"]["list"].append(mid)
        					client.sendMessage(to, "Succes Add To Staff")
        					settings["staff"]["add"] = False
        				else:
        					if msg.contentMetadata["mid"] in settings["staff"]["list"]:
        						client.sendMessage(to, "Contact Succes Add To Staff")
        						settings["staff"]["add"] = False
        			if settings["staff"]["delete"] == True:
        				if msg.contentMetadata["mid"] in settings["staff"]["list"]:
        					mid = msg.contentMetadata["mid"]
        					settings["staff"]["list"].remove(mid)
        					client.sendMessage(to, "Succes Delete To Staff")
        					settings["staff"]["delete"] = False
        				else:
        					if msg.contentMetadata["mid"] not in settings["staff"]["list"]:
        						client.sendMessage(to, "Contact Succes Delete To Staff")
        						settings["staff"]["delete"] = False
        			if settings["blacklist"]["add"] == True:
        				if msg.contentMetadata["mid"] not in settings["blacklist"]["list"]:
        					mid = msg.contentMetadata["mid"]
        					settings["blacklist"]["list"].append(mid)
        					client.sendMessage(to, "Succes Add To Blacklist")
        					settings["blacklist"]["add"] = False
        				else:
        					if msg.contentMetadata["mid"] in settings["blacklist"]["list"]:
        						client.sendMessage(to, "Contact Succes Add To Blacklist")
        						settings["blacklist"]["add"] = False
        			if settings["blacklist"]["delete"] == True:
        				if msg.contentMetadata["mid"] in settings["blacklist"]["list"]:
        					mid = msg.contentMetadata["mid"]
        					settings["blacklist"]["list"].remove(mid)
        					clientsendMessage(to, "Succes Delete To Blacklist")
        					settings["blacklist"]["delete"] = False
        				else:
        					if msg.contentMetadata["mid"] not in settings["blacklist"]["list"]:
        						client.sendMessage(to, "Contact Succes Delete To Blacklist")
        						settings["blacklist"]["delete"] = False
    except Exception as error:
    	print(error)

while True:
    try:
        ops = clientPoll.singleTrace(count=50)
        if ops is not None:
            for op in ops:
                clientBot(op)
                clientPoll.setRevision(op.revision)
    except Exception as error:
    	print(error)
        
def atend():
    print("Saving")
    with open("Log_data.json","w",encoding='utf8') as f:
        json.dump(msg_dict, f, ensure_ascii=False, indent=4,separators=(',', ': '))
    print("BYE")
atexit.register(atend)

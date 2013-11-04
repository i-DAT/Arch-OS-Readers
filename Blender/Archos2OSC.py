import socket
import OSC
import time
from lxml import etree

"""	
socket.bind(('', 1234))
socket.setblocking(0)
socket.settimeout(0.1)
"""
#lastValue = 0.0
port = 1234 
hostname = None 
address = "/rotate" 
remote = "127.0.0.1" 

def queryServer():
	tree = etree.parse("http://arch-os.scce.plymouth.ac.uk/xml_data.php?source=all_bms")
	#tree = etree.parse("http://arch-os.scce.plymouth.ac.uk/xml_data.php?source=bms_.WindVane")	
	root = tree.getroot()
	
	branches =  root.iterfind("sensor")
	result = []
	for branch in branches:
		source = branch.find("source")
		value = branch.find("value")
		if (str(source.text) == "bms_.WindVane"):
			print value.text
			#lastValue = float(value.text)
			result.append(["bms_.WindVane", value.text])
		if (str(source.text) == "bms_.WindSpeed"):
			print value.text
			result.append(["bms_.WindSpeed", value.text])
		if (str(source.text) == "bms_.OutAirTemp"):
			print value.text
			result.append(["bms_.OutAirTemp", value.text])
			#result.append(["bms_.OutAirTemp", '99.16'])
		if (str(source.text) == "bms_.Rain_Sensor"):
			print value.text
			result.append(["bms_.Rain_Sensor", value.text])

	return result

"""
_.OutAirHum bms_.OutAirHum 00:46:48 06/02/2010 90.02 http://arch-os.scce.plymouth.ac.uk/xml_description.php?source=bms_.OutAirTemp bms_.OutAirTemp 00:46:48 06/02/2010 7.25 http://arch-os.scce.plymouth.ac.uk/xml_description.php?source=bms_.Rain_Sensor bms_.
"""

		
	


# OSC send function adapted from pyKit suggestion. 
def OSCsend(remote, address, message): 
    s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    s.setblocking(0)
    print "sending", message, "to", remote, "(osc: %s)" % address 
    osc = OSC.OSCMessage() 
    osc.setAddress(address) 
    osc.append(message) 
    data = osc.getBinary() 
    s.sendto(data, remote) 



# here i try to send the value : 10 
while 1:
	#lastValue = 0.0
	result = queryServer()
	for value in result:
		#OSCsend((remote, port), "/rotate", value)
		OSCsend((remote, port), value[0], value[1])
	time.sleep(10)
	

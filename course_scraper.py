import datetime
import requests
import bs4
import re
import lxml

URLARR = ["http://www.vvz.ethz.ch/Vorlesungsverzeichnis/sucheLehrangebot.view?lang=de&search=on&semkez=", "&studiengangTyp=&deptId=&studiengangAbschnittId=&lerneinheitstitel=&lerneinheitscode=",
          "&famname=&rufname=&wahlinfo=&lehrsprache=&periodizitaet=&katalogdaten=&strukturAus=true&_strukturAus=on&search=Suchen"]
REGEX = "<a href=\\\"/Vorlesungsverzeichnis/lerneinheit.view\\?lerneinheitId=(.*?)\\\">(.*?)</a>"
NAME = "<a href=\"/Vorlesungsverzeichnis/lerneinheit.view?lerneinheitId="

a = "http://www.vvz.ethz.ch/Vorlesungsverzeichnis/sucheLehrangebot.view?lang=de&search=on&semkez=2022W&studiengangTyp=&deptId=&studiengangAbschnittId=&lerneinheitstitel=&lerneinheitscode=529-2001-02L&famname=&rufname=&wahlinfo=&lehrsprache=&periodizitaet=&katalogdaten=&_strukturAus=on&search=Suchen"

id = input()

year = datetime.datetime.today().year

flipflop = True
# Checking the last 10 Semester for the ID
i = 0
while (i < 10):
    semester = str(year) + ("W" if flipflop else "S")
    url = URLARR[0] + semester + URLARR[1] + id + URLARR[2]
    htmlContent = requests.get(url)
    hmtl_str = str(bs4.BeautifulSoup(htmlContent.text, 'lxml'))
    if (NAME in hmtl_str):
        extractedTitle = re.search(REGEX, hmtl_str).group(2)
        
        extractedTitle = extractedTitle.replace("&ouml;", "ö").replace(
            "&uuml;", "ü").replace("&auml;", "ä")
        print(extractedTitle)
    # Course wasn't found that Semester will check next.
    if (flipflop):
        year -= 1
    flipflop = not flipflop
    i += 1
    exit()


#risiko = soupRisiko.select('div[id~=accordion1601047080607]')
#zahlen = soupZahlen.select('div[class~=mod mod-text]')

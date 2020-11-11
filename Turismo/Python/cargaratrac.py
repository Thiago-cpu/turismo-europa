from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
import mysql.connector
import time
from selenium.webdriver.support import expected_conditions as EC
PATH = "C:\Program Files (x86)\chromedriver.exe"
mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  password="",
  database="europa"
)
mycursor = mydb.cursor()
mycursor.execute("SELECT * FROM destinos")

myresult = mycursor.fetchall()


driver = webdriver.Chrome(PATH)

driver.get("https://www.google.com/travel/guide/compare?q=lugares+turisticos+europa&oq=lugares+turisticos+europa&aqs=chrome.0.0l2j0i22i30l6.3982j0j4&sourceid=chrome&ie=UTF-8&dest_mid=/m/02j9z&dest_src=o&sa=X&ved=2ahUKEwjoxouwpfbsAhVkGbkGHQiwDlwQ6tEBKAQwAHoECBQQCw")

for x in myresult:
    try:
        bus = WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.CLASS_NAME, "lG0fge"))
        )
        print(x[1])
        h2 = bus.find_element_by_xpath(f"//h2[contains(string(), '{x[1]}')]")
        link =h2.find_element_by_xpath('..').find_element_by_xpath('..').get_attribute("href")
        seeall = '/see-all'
        linknew = link[ :link.find('?')] + seeall + link[link.find('?'):]
        driver.get(linknew)

        bus = WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.CLASS_NAME, "kQb6Eb"))
        )
        y = 0
        cards = bus.find_elements_by_css_selector('.Ld2paf')
        for card in cards:
            y += 900
            nombre = card.get_attribute("data-title")
            des = card.find_element_by_class_name('nFoFM').text
            driver.execute_script(f"window.scrollTo(0, {y});")
            img = card.find_element_by_tag_name('img').get_attribute('src')
            try:
                mycursor.execute(f'UPDATE atrac SET atrac.src = "{img}" where atrac.nombre = "{nombre}"')
                mydb.commit()
            except:
                print('error')
            
        driver.back()

    finally:
        print("terminó")

            








            # try:
            #     mycursor.execute(f'INSERT INTO destinos (destinos.nombre, destinos.des, destinos.src) VALUES ("{h2.text}","{desc.text}","{imgsrc}")')
            #     mydb.commit()
            # except:
            #     print('error')
    




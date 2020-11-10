from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
import mysql.connector
from selenium.webdriver.support import expected_conditions as EC
PATH = "C:\Program Files (x86)\chromedriver.exe"
mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  password="",
  database="europa"
)
mycursor = mydb.cursor()

driver = webdriver.Chrome(PATH)

driver.get("https://www.google.com/travel/guide/compare?q=lugares+turisticos+europa&oq=lugares+turisticos+europa&aqs=chrome.0.0l2j0i22i30l6.3982j0j4&sourceid=chrome&ie=UTF-8&dest_mid=/m/02j9z&dest_src=o&sa=X&ved=2ahUKEwjoxouwpfbsAhVkGbkGHQiwDlwQ6tEBKAQwAHoECBQQCw")
try:
    bus = WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.CLASS_NAME, "lG0fge"))
    )
    rows = bus.find_elements_by_class_name('zJNRF')
    for row in rows:
        cards = row.find_elements_by_class_name('gws-trips-desktop__city-card')
        for card in cards:
            link = card.find_element_by_class_name('sjglme')
            img = link.find_element_by_class_name('gsukCb')
            imgsrc = link.find_element_by_tag_name('img').get_attribute("src")
            h2 = img.find_element_by_tag_name('h2')
            desc = card.find_element_by_css_selector(".GmOjKf div:nth-of-type(1)")
            print(h2.text +', ' + desc.text + ', ' + imgsrc)
            try:
                mycursor.execute(f'INSERT INTO destinos (destinos.nombre, destinos.des, destinos.src) VALUES ("{h2.text}","{desc.text}","{imgsrc}")')
                mydb.commit()
            except:
                print('error')


finally:
    driver.quit()




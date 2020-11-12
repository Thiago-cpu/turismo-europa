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
mycursor.execute("SELECT * FROM destinos where destinos.destinos_id > 22")

myresult = mycursor.fetchall()


driver = webdriver.Chrome(PATH)

driver.get("https://www.google.com/travel/guide/compare?q=lugares+turisticos+europa&oq=lugares+turisticos+europa&aqs=chrome.0.0l2j0i22i30l6.3982j0j4&sourceid=chrome&ie=UTF-8&dest_mid=/m/02j9z&dest_src=o&sa=X&ved=2ahUKEwjoxouwpfbsAhVkGbkGHQiwDlwQ6tEBKAQwAHoECBQQCw")

for x in myresult:

    driver.get("https://www.google.com/travel/hotels/" + x[1])
    for i in range(0, 10):
      try:
        container = WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.CLASS_NAME, "l5cSPd"))
        )
        card = container.find_elements_by_css_selector('.BcKagd')[i]
        link = card.find_element_by_css_selector('.PVOOXe').get_attribute("href")
        nombre = card.find_element_by_css_selector('.ykx2he').text
        estrellas = card.find_elements_by_css_selector('.NPG4zc > span')[0].text
        img = card.find_elements_by_css_selector('.VLNtXe > .Un8Fvc > .SJyhnc > div > div > img')[0].get_attribute("src")
        precio = card.find_element_by_css_selector('.prxS3d').get_attribute("textContent")
        driver.get(link)
        lugar = WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.CLASS_NAME, "K4nuhf"))
        )
        lugar = lugar.find_element_by_css_selector('.CFH2De').text
        driver.get("https://plus.codes/9C3XGV5C+6Q")
        inp = WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.ID, 'search-input'))
        )
        inp.send_keys(lugar)
        inp.send_keys(Keys.ENTER)
        time.sleep(2)
        btn = WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.CLASS_NAME, 'expand'))
        )
        btn.click()
        time.sleep(1)
        latlng = driver.find_element_by_css_selector('.latlng').text
        mycursor.execute(f'INSERT INTO hoteles values (DEFAULT, {x[0]}, "{nombre}", "{estrellas}", {precio[precio.find("$") + 2:]}, "{lugar}", {latlng[:latlng.find(",")]}, {latlng[latlng.find(",") + 1:]}, "{link}", "{img}")')
        mydb.commit()
        driver.get("https://www.google.com/travel/hotels/" + x[1])
        print(precio)
      except:
        print("Error")
        driver.get("https://www.google.com/travel/hotels/" + x[1])




      


            








            # try:
            #     mycursor.execute(f'INSERT INTO destinos (destinos.nombre, destinos.des, destinos.src) VALUES ("{h2.text}","{desc.text}","{imgsrc}")')
            #     mydb.commit()
            # except:
            #     print('error')
    




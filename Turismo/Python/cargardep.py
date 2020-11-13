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
mycursor.execute("SELECT * FROM destinos where destinos.destinos_id = 56")

myresult = mycursor.fetchall()


driver = webdriver.Chrome(PATH)

driver.get("https://www.google.com/travel/hotels/Brujas?tcfs=EhIKCC9tLzBrNDI0EgZCcnVqYXM&hrf=CgUIsG0QACIDQVJTKhYKBwjkDxALGA4SBwjkDxALGA8YASgAsAEAWAFgAGgBmgESCggvbS8wazQyNBIGQnJ1amFzogESCggvbS8wazQyNBIGQnJ1amFzqgEWCgIIIRICCBUSAggNEgIIZxICCC8YAaoBCgoCCBESAggqGAGqAQwKAwipARIDCKoBGAGqAQoKAghQEgIITxgBkgECIAE&ved=0CAAQ5JsGahcKEwiggs-Mpv7sAhUAAAAAHQAAAAAQDQ&ictx=3&hl=es-419&gl=ar&g2lb=2502548%2C4258168%2C4270442%2C4271060%2C4306835%2C4317915%2C4328159%2C4371335%2C4419364%2C4428792%2C4429192%2C4433754%2C4447566%2C4452574%2C4456077%2C4462650%2C4463263%2C4464070%2C4270859%2C4284970%2C4291517%2C4412693&un=1&rp=OAE")
for x in myresult:

    driver.get("https://www.google.com/travel/hotels/" + x[1] + '?tcfs=EhMKCC9tLzA4OTY2Egdaw7pyaWNo')
    for i in range(7, 10):

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
      mycursor.execute(f'INSERT INTO hoteles values (DEFAULT, {x[0]}, "{nombre}", "{estrellas}", {precio[4:]}, "{lugar}", {latlng[:latlng.find(",")]}, {latlng[latlng.find(",") + 1:]}, "{link}", "{img}")')
      mydb.commit()
      driver.get("https://www.google.com/travel/hotels/Brujas?tcfs=EhIKCC9tLzBrNDI0EgZCcnVqYXM&hrf=CgUIsG0QACIDQVJTKhYKBwjkDxALGA4SBwjkDxALGA8YASgAsAEAWAFgAGgBmgESCggvbS8wazQyNBIGQnJ1amFzogESCggvbS8wazQyNBIGQnJ1amFzqgEWCgIIIRICCBUSAggNEgIIZxICCC8YAaoBCgoCCBESAggqGAGqAQwKAwipARIDCKoBGAGqAQoKAghQEgIITxgBkgECIAE&ved=0CAAQ5JsGahcKEwiggs-Mpv7sAhUAAAAAHQAAAAAQDQ&ictx=3&hl=es-419&gl=ar&g2lb=2502548%2C4258168%2C4270442%2C4271060%2C4306835%2C4317915%2C4328159%2C4371335%2C4419364%2C4428792%2C4429192%2C4433754%2C4447566%2C4452574%2C4456077%2C4462650%2C4463263%2C4464070%2C4270859%2C4284970%2C4291517%2C4412693&un=1&rp=OAE")
      print(precio)


        




      


            








            # try:
            #     mycursor.execute(f'INSERT INTO destinos (destinos.nombre, destinos.des, destinos.src) VALUES ("{h2.text}","{desc.text}","{imgsrc}")')
            #     mydb.commit()
            # except:
            #     print('error')
    




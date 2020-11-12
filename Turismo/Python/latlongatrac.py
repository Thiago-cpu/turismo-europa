from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
import mysql.connector
import time
import subprocess
from selenium.webdriver.support import expected_conditions as EC
PATH = "C:\Program Files (x86)\chromedriver.exe"
mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  password="",
  database="europa"
)
mycursor = mydb.cursor()
mycursor.execute("select atrac.nombre, destinos.nombre, atrac.atrac_id from atrac inner join destinos on destinos.destinos_id = atrac.destinos_id and atrac.atrac_id > 1385")

myresult = mycursor.fetchall()


driver = webdriver.Chrome(PATH)


for x in myresult:
    try:
        driver.get("https://plus.codes/9C3XGV5C+6Q")
        inp = WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.ID, 'search-input'))
        )
        inp.send_keys(x[1] + ', ' + x[0])
        inp.send_keys(Keys.ENTER)
        time.sleep(2)
        btn = driver.find_element_by_css_selector('.expand')
        btn.click()
        time.sleep(1)
        latlng = driver.find_element_by_css_selector('.latlng').text
        print(latlng[:latlng.find(",")])
        print(latlng[latlng.find(",") + 1:])
        mycursor.execute(f'UPDATE atrac SET atrac.latitud = {latlng[:latlng.find(",")]}, atrac.longitud = {latlng[latlng.find(",") + 1:]} where atrac.atrac_id = {x[2]}')
        mydb.commit()
        print("Submit")
        if x[2] == 4456:
            subprocess.call("shutdown -s")
    except:
        print("Error")
        if x[2] == 4456:
            subprocess.call("shutdown -s")


    




    # menu = WebDriverWait(driver, 10).until(
    # EC.presence_of_element_located((By.ID, 'content-container'))
    # )
    # time.sleep(2)
    # men = menu.find_element_by_class_name('searchbox-button')
    # men.click()
    # time.sleep(2)
    # compartir = driver.find_element(By.XPATH, "//*[text()= 'Compartir o insertar el mapa']")
    # buton = compartir.find_element_by_xpath('..')
    # buton.click()
    # time.sleep(5)
    # incorporar = driver.find_element(By.XPATH, "//*[text()= 'Insertar un mapa']")
    # incorporar.click()
    # time.sleep(5)
    # inp = driver.find_element_by_css_selector('.section-embed-map-input').get_attribute('value')
    # start = inp.find('h')
    # end = inp.find('"',15)
    # mapa = inp[start:end]
    # try:
    #     mycursor.execute(f'UPDATE destinos SET destinos.map = "{mapa}" where destinos.destinos_id = {x[0]}')
    #     mydb.commit()
    # except:
    #     print('error')

            








            # try:
            #     mycursor.execute(f'INSERT INTO destinos (destinos.nombre, destinos.des, destinos.src) VALUES ("{h2.text}","{desc.text}","{imgsrc}")')
            #     mydb.commit()
            # except:
            #     print('error')
    




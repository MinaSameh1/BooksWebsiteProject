import pandas as pd
from selenium import webdriver
import requests

all_books = pd.read_json('book_best_001_025.jl', lines=True)

driver = webdriver.Firefox()

for i in range(0,594):
    print("cover image number:" + str(i) )
    driver.get(all_books["url"][i])
    img = driver.find_element_by_id("coverImage")
    src = img.get_attribute('src')  

    cover = open('covers/cover' + str(i) + '.png', 'wb')
    cover.write(requests.get(src).content)
    cover.close
    print("Done! jumping to next one")
driver.close()

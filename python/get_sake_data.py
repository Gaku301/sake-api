from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.common.by import By
import requests


# Init Selenium
options = Options()
options.add_experimental_option('detach', True)
driver = webdriver.Chrome(options=options,service=Service(ChromeDriverManager().install()))

# Open target page
driver.get('https://www.j-sake.com/321772568962')

prefectures = [
    '北海道', '青森県', '岩手県', '宮城県',
    # '秋田県', '山形県', '福島県', '茨城県',
    # '栃木県', '群馬県', '埼玉県', '千葉県',
    # '東京都', '神奈川県', '新潟県', '富山県',
    # '石川県', '福井県', '山梨県', '長野県',
    # '岐阜県', '静岡県', '愛知県', '三重県',
    # '滋賀県', '京都府', '大阪府', '兵庫県',
    # '奈良県', '和歌山県', '鳥取県', '島根県',
    # '岡山県', '広島県', '山口県', '徳島県',
    # '香川県', '愛媛県', '高知県', '福岡県',
    # '佐賀県', '長崎県', '熊本県', '大分県',
    # '宮崎県', '鹿児島県', '沖縄県'
]

# Get all sake datas
all_info = {}
for prefecture in prefectures:
    all_info[prefecture] = {}
    # Get target prefecture's p_tags(this has kuramoto information)
    p_tags = driver.find_elements(by=By.XPATH, value='//h3[contains(text(), "'+prefecture+'")]/following-sibling::p')
    for index, p_tag in enumerate(p_tags):
        try:
            # Get target prefecture's kuramoto
            kuramot_name = p_tag.find_element(by=By.TAG_NAME, value='strong').text
            all_info[prefecture][kuramot_name] = []
            # Get target kuramoto's sake
            li_tags = driver.find_elements(by=By.XPATH, value='//h3[contains(text(), "'+prefecture+'")]/following-sibling::p['+str(index+1)+']/following-sibling::ul[1]/li')
            sakes = []
            for li in li_tags:
                sakes.append(li.text)
            all_info[prefecture][kuramot_name] = sakes
        except:
            continue

# Close window
driver.quit()

# Call Laravel API
url = 'http://localhost:8080/api/v1/sake_info'
res = requests.post(url, json={'sake_info': all_info})

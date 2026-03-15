from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.options import Options
from webdriver_manager.chrome import ChromeDriverManager

options = Options()
options.add_argument("--start-maximized")
options.add_argument("--disable-blink-features=AutomationControlled")

driver = webdriver.Chrome(
    service=Service(ChromeDriverManager().install()),
    options=options
)

url = "https://www.seek.com.au/job/89527544"
driver.get(url)

try:
    wait = WebDriverWait(driver, 20)

    job_div = wait.until(
        EC.presence_of_element_located(
            (By.XPATH, '//div[@data-automation="jobAdDetails"]')
        )
    )

    print("\n=== JOB DETAILS ===\n")
    print(job_div.text)

except Exception as e:
    print("Failed to locate job details:", e)

driver.quit()

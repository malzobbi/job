from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.options import Options
from webdriver_manager.chrome import ChromeDriverManager
import tkinter as tk
import customtkinter as ctk
from tkinter import ttk, messagebox
#import mysql.connector
#import mysql.connector.locales.eng.client_error
from datetime import date
import sys
import os
import locale
import pymysql


try:
    locale.setlocale(locale.LC_ALL, 'en_AU.UTF-8')
except:
    locale.setlocale(locale.LC_ALL, '')


def resource_path(relative_path):
    try:
        base_path = sys._MEIPASS
    except Exception:
        base_path = os.path.abspath(".")

    return os.path.join(base_path, relative_path)

if len(sys.argv) > 1:
    id_company = int(sys.argv[1])  # This is a string
else:
    id_company = 1


#messagebox.showinfo("Success", id_company)
def abstract_job():
    options = Options()
    options.add_argument("--start-maximized")
    options.add_argument("--disable-blink-features=AutomationControlled")

    driver = webdriver.Chrome(
        service=Service(ChromeDriverManager().install()),
        options=options
    )

    url = entry_link.get()
    driver.get(url)

    try:
        wait = WebDriverWait(driver, 20)
        
        page_title=driver.title
        val = page_title.split(" Job in ", 1)
        entry_title.insert(tk.END,val[0])
        location_text = val[1].strip() if len(val) > 1 else ""

        # Detect state
        states = ["NSW", "QLD", "VIC", "SA", "WA", "TAS", "NT", "ACT"]
        state_found = "Others"


        for state in states:
            
            if state in location_text:
                state_found=state
                break
                print(state_found)

        location_var.set(state_found)

                

        job_div = wait.until(
            EC.presence_of_element_located(
                (By.XPATH, '//div[@data-automation="jobAdDetails"]')
            )
        )
        text_details.insert(tk.END, "\n=== JOB DETAILS ===\n")
        text_details.insert(tk.END, job_div.text)
        entry_subtitle.insert(tk.END,page_title)
        

    except Exception as e:
        print("Failed to locate job details:", e)

    driver.quit()

# -----------------------------
# MySQL Configuration
# -----------------------------
DB_CONFIG = {
    "host": "localhost",      # change if needed
    "user": "root",           # your mysql user
    "password": "",           # your mysql password
    "database": "job_portal"
}

# -----------------------------
# Insert Data Function
# -----------------------------

def submit_job(id_company):
    try:
        conn = pymysql.connect(
        host=DB_CONFIG["host"],
        user=DB_CONFIG["user"],
        password=DB_CONFIG["password"],
        database=DB_CONFIG["database"],
        cursorclass=pymysql.cursors.DictCursor
        )

        cursor = conn.cursor()

        sql = """
        INSERT INTO job_post
        (id_company, jobtitle, description, subtitle, location,
         qualification, experience, createdat, apply_link, job_status)
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, 0)
        """

        values = (
            id_company,                         
            entry_title.get(),
            text_details.get("1.0", tk.END).strip(),
            entry_subtitle.get(),
            location_var.get(),
            entry_qualification.get(),
            experience_var.get(),
            date.today(),
            entry_link.get()
        )

        cursor.execute(sql, values)
        conn.commit()

        messagebox.showinfo("Success", "Job posted successfully!")
        # Clear fields
        entry_title.delete(0, tk.END)
        entry_subtitle.delete(0, tk.END)
        entry_link.delete(0, tk.END)
        location_var.set("NSW")
        text_details.delete("1.0", tk.END)
        experience_var.set(1)


    except Exception as e:
        messagebox.showerror("Error", str(e))

    finally:
        try:
            cursor.close()
            conn.close()
        except:
            pass
        


def exit_app():
    root.destroy()

# -----------------------------
# GUI Setup
# -----------------------------
def start_apply_job(id_company):
    global root, entry_link, entry_title, text_details, entry_subtitle
    global location_var, experience_var, entry_qualification

    
    root = tk.Tk()
    root.title("Job Portal - Add Job")
    root.iconbitmap("favicon_io/favicon.ico")
    root.state("zoomed")


    # Main container
    main = ttk.Frame(root, padding=10)
    main.grid(row=0, column=0, sticky="nsew")

    root.columnconfigure(0, weight=1)
    root.rowconfigure(0, weight=1)

    # Logo
    logo = tk.PhotoImage(file="favicon_io/top.png")
    ttk.Label(main, image=logo).grid(row=0, column=0, columnspan=2, pady=10)
    #ttk.Label(main, text="Job Portal by AusTech", font=("Segoe UI", 16, "bold")).grid(row=0, column=0, columnspan=3, padx=10, pady=10)
    #fill-in the skills in the combobox
    conn = pymysql.connect(
            host=DB_CONFIG["host"],
            user=DB_CONFIG["user"],
            password=DB_CONFIG["password"],
            database=DB_CONFIG["database"],
            cursorclass=pymysql.cursors.DictCursor
            )
    cursor = conn.cursor()
    cursor.execute("SELECT skill_name FROM skills ORDER BY skill_name ASC")
    rows = cursor.fetchall()
    skills = [row["skill_name"] for row in rows]


    # WELCOME **** MESSAGE
    cursor.execute("SELECT companyname FROM company WHERE id_company = %s",(id_company,))
    result = cursor.fetchone()
    company_name = result["companyname"]
    welcome_label = ttk.Label(
        root,
        text=f"Welcome {company_name}",
        font=("Segoe UI", 16, "bold"),
        foreground="#2c3e50"
    )
    welcome_label.grid(row=0, column=1,columnspan=4, pady=(0, 2), sticky="w")

    cursor.close()
    conn.close()
    # -----------------------------
    # Job URL
    # -----------------------------
    ttk.Label(main, text="Job URL Link").grid(row=1, column=0, sticky="w", pady=5)
    entry_link = ttk.Entry(main, width=50)
    entry_link.grid(row=1, column=1, pady=5, sticky="ew")

    #company = ttk.Entry(main, width=50)
    #company.grid(row=1, column=1, pady=2, sticky="ew")
    #company.insert(0, id_company)

    # Button Abstract Job
    read_btn = tk.Button(main, text="Abstract Job", command=abstract_job, bg="blue", fg="white")
    read_btn.grid(row=2, column=0, pady=10, sticky="w")



    # Separator
    ttk.Separator(main, orient="horizontal").grid(row=3, column=0, columnspan=2, sticky="ew", pady=10)

    # -----------------------------
    # Job Title
    # -----------------------------
    ttk.Label(main, text="Job Title").grid(row=4, column=0, sticky="w")
    entry_title = ttk.Entry(main, width=50)
    entry_title.grid(row=4, column=1, pady=5, sticky="ew")

    # -----------------------------
    # Job Subtitle
    # -----------------------------
    ttk.Label(main, text="Job Sub-title").grid(row=5, column=0, sticky="w")
    entry_subtitle = ttk.Entry(main, width=50)
    entry_subtitle.grid(row=5, column=1, pady=5, sticky="ew")

    # -----------------------------
    # Location
    # -----------------------------
    ttk.Label(main, text="Location").grid(row=6, column=0, sticky="w")
    location_var = tk.StringVar()
    locations = ["NSW", "QLD", "SA", "WA", "TAS", "VIC", "NT", "ACT", "Others"]
    location_dropdown = ttk.Combobox(main, textvariable=location_var, values=locations)
    location_dropdown.grid(row=6, column=1, pady=5, sticky="w")
    location_dropdown.current(0)

    # -----------------------------
    # Experience
    # -----------------------------
    ttk.Label(main, text="Years of Experience").grid(row=7, column=0, sticky="w")
    experience_var = tk.IntVar(value=1)
    experience_spin = ttk.Spinbox(main, from_=1, to=50, textvariable=experience_var, width=10)
    experience_spin.grid(row=7, column=1, pady=5, sticky="w")

    # -----------------------------
    # Qualification
    # -----------------------------
    ttk.Label(main, text="Qualifications").grid(row=8, column=0, sticky="w")
    entry_qualification = ttk.Combobox(main, values=skills, width=47)
    entry_qualification.grid(row=8, column=1, pady=5, sticky="w")
    entry_qualification.current(3)

    # -----------------------------
    # Job Details Text Area
    # -----------------------------
    ttk.Label(main, text="More Job Details").grid(row=9, column=0, sticky="nw", pady=5)

    text_frame = ttk.Frame(main)
    text_frame.grid(row=9, column=1, pady=5, sticky="nsew")

    main.columnconfigure(1, weight=1)
    main.rowconfigure(9, weight=1)

    scrollbar_y = ttk.Scrollbar(text_frame, orient="vertical")
    scrollbar_y.grid(row=0, column=1, sticky="ns")

    scrollbar_x = ttk.Scrollbar(text_frame, orient="horizontal")
    scrollbar_x.grid(row=1, column=0, sticky="ew")

    text_details = tk.Text(
        text_frame,
        height=8,
        wrap="none",
        yscrollcommand=scrollbar_y.set,
        xscrollcommand=scrollbar_x.set
    )
    text_details.grid(row=0, column=0, sticky="nsew")

    text_frame.columnconfigure(0, weight=1)
    text_frame.rowconfigure(0, weight=1)

    scrollbar_y.config(command=text_details.yview)
    scrollbar_x.config(command=text_details.xview)

    # Button Submit Job
    submit_btn = tk.Button(main, text="Submit Job", command=lambda:submit_job(id_company), bg="#4CAF50", fg="white")
    submit_btn.grid(row=10, column=1, pady=10, sticky="e")

    # Button Exit
    submit_btn = tk.Button(main, text="    EXIT     ", command=exit_app, bg="#000000", fg="white")
    submit_btn.grid(row=10, column=0, pady=10, sticky="e")

    root.mainloop()

if __name__ == "__main__":
    start_apply_job(1)

# login.py
import tkinter as tk
from tkinter import ttk, messagebox
import pymysql
import bcrypt
import apply_job
from PIL import Image, ImageTk
import sys
import os
import locale

# -----------------------------
# Locale and resource path setup
# -----------------------------
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

# -----------------------------
# Database configuration
# -----------------------------
DB_CONFIG = {
    "host": "74.91.195.153",
    "user": "austechi_yazan",
    "password": "BarakatHarakat2025",
    "database": "austechi_job_portal"
}

# -----------------------------
# Main login GUI function
# -----------------------------
def start_login():
    root = tk.Tk()
    root.title("Company Login")
    try:
        root.iconbitmap(resource_path("favicon_io/favicon.ico"))
    except Exception:
        pass

    root.geometry("550x350")
    root.resizable(False, False)

    # Load background image
    bg_image = Image.open(resource_path("favicon_io/bg.png"))
    bg_image = ImageTk.PhotoImage(bg_image)
    bg_label = tk.Label(root, image=bg_image)
    bg_label.place(x=0, y=0, relwidth=1, relheight=1)

    # Centered content frame
    content = ttk.Frame(root)
    content.place(relx=0.5, rely=0.5, anchor="center")

    # Email label + entry
    ttk.Label(content, text="Email").grid(row=0, column=0, sticky="w", pady=5)
    entry_email = ttk.Entry(content, width=35)
    entry_email.grid(row=0, column=1, pady=5)

    # Password label + entry
    ttk.Label(content, text="Password").grid(row=1, column=0, sticky="w", pady=5)
    entry_password = ttk.Entry(content, width=35, show="*")
    entry_password.grid(row=1, column=1, pady=5)

    # Show/hide password
    show_var = tk.BooleanVar()
    def toggle_password():
        if show_var.get():
            entry_password.config(show="")
        else:
            entry_password.config(show="*")
    ttk.Checkbutton(
        content,
        text="Show password",
        variable=show_var,
        command=toggle_password
    ).grid(row=2, column=1, sticky="w", pady=5)

    # -----------------------------
    # Login logic
    # -----------------------------
    def login():
        email = entry_email.get().strip()
        password = entry_password.get()

        if not email or not password:
            messagebox.showerror("Error", "Please enter email and password")
            return

        try:
            conn = pymysql.connect(
                host=DB_CONFIG["host"],
                user=DB_CONFIG["user"],
                password=DB_CONFIG["password"],
                database=DB_CONFIG["database"],
                cursorclass=pymysql.cursors.DictCursor
            )
            cursor = conn.cursor()
            cursor.execute(
                "SELECT id_company, password FROM company WHERE email = %s",
                (email,)
            )
            user = cursor.fetchone()

            if not user:
                messagebox.showerror("Login Failed", "Invalid email or password")
                return

            hashed_password = user["password"].encode("utf-8")
            if bcrypt.checkpw(password.encode("utf-8"), hashed_password):
                company_id = user["id_company"]
                root.destroy()
                apply_job.start_apply_job(company_id)
            else:
                messagebox.showerror("Login Failed", "Invalid email or password")

        except Exception as e:
            messagebox.showerror("Error", str(e))
        finally:
            if conn.open:  # pymysql uses .open
                cursor.close()
                conn.close()

    # -----------------------------
    # Gradient button function
    # -----------------------------
    def gradient_button(parent, text, command,
                        width=160, height=40,
                        color1="#2ecc71", color2="#27ae60"):

        canvas = tk.Canvas(parent, width=width, height=height, highlightthickness=0)
        canvas.grid()

        for i in range(height):
            r1, g1, b1 = parent.winfo_rgb(color1)
            r2, g2, b2 = parent.winfo_rgb(color2)
            r = int(r1 + (r2 - r1) * i / height) >> 8
            g = int(g1 + (g2 - g1) * i / height) >> 8
            b = int(b1 + (b2 - b1) * i / height) >> 8
            color = f"#{r:02x}{g:02x}{b:02x}"
            canvas.create_line(0, i, width, i, fill=color)

        canvas.create_text(width//2, height//2, text=text,
                           fill="white", font=("Segoe UI", 11, "bold"))
        canvas.bind("<Button-1>", lambda e: command())
        canvas.config(cursor="hand2")
        return canvas

    # Create login button
    login_btn = gradient_button(content, text="Login", command=login)
    login_btn.grid(row=3, column=1, pady=15)

    root.mainloop()

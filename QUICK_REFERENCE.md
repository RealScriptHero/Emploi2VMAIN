# 🚀 Quick Start Guide - OFPPT System

## ⚡ Start the System

```bash
cd /home/vedrix/Desktop/emploi
php artisan serve --port=8000
```

Open: **http://localhost:8000**

---

## 📋 Available Pages

### Management Pages (All Connected to Database)

| Page | URL | Description |
|------|-----|-------------|
| 🎓 Modules | `/modules` | Training modules with search & CSV |
| 🏢 Centres | `/centres` | Training centers management |
| 🏫 Rooms | `/salles` | Training rooms (cascades from centre) |
| 👥 Groups | `/groupes` | Student groups by level |
| 👨‍🏫 Trainers | `/formateurs` | Trainer information & email |
| 📅 Schedule | `/emploi-du-temps` | Training sessions with time validation |
| 📚 Internships | `/stages` | Stage management with date validation |
| 🚫 Trainer Absences | `/absences/formateurs` | Track trainer absences |
| 🚫 Group Absences | `/absences/groupes` | Track group absences |
| 📊 Progress | `/avancements` | Track trainer progress (0-100%) |

---

## 🔧 Key Features

✅ **Real-time Database Sync** - All changes immediately visible  
✅ **Zero localStorage** - Everything stored on server  
✅ **Form Validation** - Email, dates, times, percentages  
✅ **Cascading Dropdowns** - Smart field dependencies  
✅ **Professional UI** - Responsive, modern design  
✅ **Loading States** - Spinners during data fetch  
✅ **Error Handling** - User-friendly error messages  

---

## 🎬 Using the Pages

### Add New Record
1. Click **"Add"** button (top right)
2. Fill form fields
3. Click **"Save"**
4. Table refreshes automatically

### Edit Record
1. Click **pencil icon** on row
2. Modal opens with current data
3. Modify fields
4. Click **"Update"**
5. Table updates automatically

### Delete Record
1. Click **trash icon** on row
2. Confirm deletion
3. Record removed immediately

---

## 🗂️ Database Structure

### Tables (13 Total)

**Core Tables:**
- utilisateurs (Users)
- centres (Training centers)
- salles (Training rooms)
- groupes (Student groups)
- modules (Training modules)
- formateurs (Trainers)
- emploi_du_temps (Schedule)
- stages (Internships)
- absence_formateurs (Trainer absences)
- absence_groupes (Group absences)
- avancements (Progress tracking)

**Sample Data:** 50+ records seeded

---

## 🔌 API Access

All data available via REST API:

```bash
# Get all data
curl http://localhost:8000/api/modules
curl http://localhost:8000/api/centres
curl http://localhost:8000/api/formateurs

# Get specific record
curl http://localhost:8000/api/modules/1

# Create new
curl -X POST http://localhost:8000/api/modules \
  -H "Content-Type: application/json" \
  -d '{"codeModule":"WEB101","nomModule":"Web Dev","volumeHoraire":60,"semestre":1}'

# Update
curl -X PUT http://localhost:8000/api/modules/1 \
  -H "Content-Type: application/json" \
  -d '{"volumeHoraire":80}'

# Delete
curl -X DELETE http://localhost:8000/api/modules/1
```

---

## ✅ Validation Rules

### Email Fields
- Must be valid email format
- Must be unique
- Required

### Date Fields
- Valid date format (YYYY-MM-DD)
- End date >= Start date
- Required

### Time Fields
- Valid time format (HH:MM)
- End time > Start time
- Required

### Percentage Fields
- Number between 0-100
- Required

### Capacity Fields
- Positive integer > 0
- Required

---

## 🛠️ Troubleshooting

**Pages show 500 error?**
- Check Laravel server is running: `ps aux | grep "php artisan"`
- Check database connection in `.env`

**API not responding?**
- Verify server is running on port 8000
- Check `http://localhost:8000/api/modules` directly

**Changes not saving?**
- Check browser console for errors (F12)
- Verify database is accessible
- Check file permissions on storage/logs

**Dropdowns empty?**
- Reload page (Ctrl+R)
- Check if related records exist in database
- Verify relationships in Eloquent models

---

## 📊 Example Data

### Modules
- WEB101: Web Development (60 hours)
- PHP201: PHP Advanced (40 hours)
- DB301: Database Design (50 hours)

### Centres
- Centre Casablanca: Casablanca
- Centre Rabat: Rabat
- Centre Fès: Fès

### Groups
- TDI-1A: Digital Tech - 1st Year
- DEV-2A: Development - 2nd Year
- RES-1A: Networks - 1st Year

### Trainers
- Ahmed Hassan (PHP Specialist)
- Fatima Ben Said (Database Expert)
- Mohamed El Kach (Web Developer)

---

## 📱 Browser Compatibility

✅ Chrome (Latest)  
✅ Firefox (Latest)  
✅ Safari (Latest)  
✅ Edge (Latest)  
✅ Mobile browsers  

---

## 🔐 Security Features

- ✅ CSRF token protection
- ✅ SQL injection prevention (Eloquent)
- ✅ Input validation
- ✅ Secure database queries
- ✅ Proper error handling
- ✅ No sensitive data in logs

---

## 📚 File Locations

```
/home/vedrix/Desktop/emploi/
├── resources/views/           [Frontend pages]
├── app/Models/                [Database models]
├── app/Http/Controllers/      [API controllers]
├── routes/                    [Web & API routes]
├── database/                  [Migrations & seeders]
└── storage/logs/              [Application logs]
```

---

## 🎯 Common Tasks

### Search/Filter
Most pages support search:
- Type in search box
- Results filter in real-time
- Case-insensitive

### Pagination
Large datasets are paginated:
- Navigate between pages
- Results show per page

### Sorting
Click column headers:
- Ascending/descending
- Works with search

### Export
Modules page supports:
- CSV export
- CSV import

---

## 📞 Need Help?

### Check Logs
```bash
tail -f /home/vedrix/Desktop/emploi/storage/logs/laravel.log
```

### Database Status
```bash
mysql -u root -p1234 -e "USE ofppt; SHOW TABLES;"
```

### Server Status
```bash
ps aux | grep "php artisan"
lsof -i :8000
```

---

## 🎓 Educational Use

This system demonstrates:
- ✅ Laravel MVC architecture
- ✅ RESTful API design
- ✅ Alpine.js reactive components
- ✅ Tailwind CSS responsive design
- ✅ Database relationships
- ✅ Form validation
- ✅ Error handling
- ✅ CSRF protection

---

**Status: ✅ FULLY OPERATIONAL**

Everything you need to manage the OFPPT training system is ready to use!

# Quick Start Guide - OFPPT Training Management System

## Starting the Application

### 1. Start Laravel Development Server
```bash
cd /home/vedrix/Desktop/emploi
php artisan serve --port=8000
```

The application will be available at: `http://localhost:8000`

### 2. Database Setup (Already Done)
- Database: `ofppt`
- User: `root`
- Password: `1234`
- Host: `127.0.0.1`

All migrations have been run and sample data has been seeded.

## Available Pages

### 1. Modules Page
**URL**: `http://localhost:8000/modules`

**Features**:
- View all modules from the database
- Search modules by code or name
- Add new modules via form
- Edit existing modules
- Delete modules
- Export to CSV
- Import from CSV

**Sample Actions**:
```bash
# Get all modules from API
curl http://localhost:8000/api/modules

# Add a new module
curl -X POST http://localhost:8000/api/modules \
  -H "Content-Type: application/json" \
  -d '{
    "nomModule": "Python Programming",
    "codeModule": "DEV-PY-001",
    "volumeHoraire": 50,
    "semestre": "4"
  }'
```

## API Usage Examples

### Getting Data

**List all modules**
```bash
curl http://localhost:8000/api/modules
```

**List all stages with related data**
```bash
curl http://localhost:8000/api/stages
```

**Get schedule by group**
```bash
curl http://localhost:8000/api/emploi-du-temps/by-group/TSSFC-A1
```

**Get schedule by trainer**
```bash
curl http://localhost:8000/api/emploi-du-temps/by-formateur/1
```

### Creating Data

**Create a new group**
```bash
curl -X POST http://localhost:8000/api/groupes \
  -H "Content-Type: application/json" \
  -d '{
    "nomGroupe": "TSDEV-C1",
    "filiere": "Technicien Spécialisé en Développement Digital",
    "niveau": "1ère année"
  }'
```

**Create a new trainer**
```bash
curl -X POST http://localhost:8000/api/formateurs \
  -H "Content-Type: application/json" \
  -d '{
    "nom": "Karim",
    "prenom": "Ali",
    "specialite": "Web Development",
    "telephone": "06 87 65 43 21",
    "email": "karim@ofppt.org"
  }'
```

### Updating Data

**Update a module**
```bash
curl -X PUT http://localhost:8000/api/modules/1 \
  -H "Content-Type: application/json" \
  -d '{
    "volumeHoraire": 60,
    "semestre": "4"
  }'
```

### Deleting Data

**Delete a module**
```bash
curl -X DELETE http://localhost:8000/api/modules/1
```

## Database Structure

### Key Tables

**modules**: Training courses
- codeModule (unique)
- nomModule
- volumeHoraire
- semestre

**formateurs**: Trainers/instructors
- nom, prenom
- email
- specialite
- telephone

**groupes**: Student groups
- nomGroupe
- filiere
- niveau

**centres**: Training centers
- nomCentre
- ville
- adresse

**salles**: Classrooms
- nomCentre, ville, adresse
- centre_id (foreign key)

**emploi_du_temps**: Schedule
- jour
- pour (group name)
- heureDebut, heureFin
- formateur_id, module_id, salle_id

**stages**: Internships
- dateDebut, dateFin
- groupe_id, formateur_id

**absence_formateurs**: Trainer absences
- dateAbsence
- motif
- formateur_id

**absence_groupes**: Group absences
- dateAbsence
- motif
- groupe_id

**avancements**: Progress tracking
- pourcentage
- dateLastUpdate
- modifie_id, formateur_id

## Common API Responses

### Success Response (Create)
```json
{
  "id": 1,
  "nomModule": "PHP et Laravel",
  "codeModule": "DEV-PHP-001",
  "volumeHoraire": 50,
  "semestre": "3",
  "created_at": "2026-02-17T12:12:19.000000Z",
  "updated_at": "2026-02-17T12:12:19.000000Z"
}
```

### Error Response
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "codeModule": ["The code module has already been taken."]
  }
}
```

## Validation Rules

### Modules
- **nomModule**: Required, max 255 chars
- **codeModule**: Required, unique, max 255 chars
- **volumeHoraire**: Integer, minimum 1
- **semestre**: Required, max 255 chars

### Formateurs
- **nom**: Required, max 255 chars
- **prenom**: Required, max 255 chars
- **email**: Required, unique, valid email
- **specialite**: Optional, max 255 chars
- **telephone**: Optional, max 255 chars

### Stages
- **dateDebut**: Required, valid date
- **dateFin**: Required, valid date, after dateDebut
- **groupe_id**: Required, must exist in groupes table
- **formateur_id**: Required, must exist in formateurs table

## Sample Data Available

The database is pre-populated with:
- 8 modules across different specializations
- 5 trainers with different specialties
- 5 student groups
- 4 training centers
- 4 classrooms
- Sample schedule entries
- Absence records
- Progress tracking records
- Stage/internship records

## Tips & Best Practices

1. **Always use PUT for updates**, not PATCH
2. **Date format**: Use YYYY-MM-DD for dates
3. **Time format**: Use HH:MM for times
4. **Passwords**: Hashed with bcrypt (for users)
5. **Foreign keys**: Ensure related records exist first

## Troubleshooting

**"Column not found" error**
- Check that column names match exactly (case-sensitive)
- Use codeModule not code, nomModule not name, etc.

**"Foreign key constraint failed"**
- Ensure the related record exists first
- Example: Create centre before creating salle with centre_id

**"Unique constraint violation"**
- Check for duplicate values
- Example: codeModule must be unique per module

**Server not running**
```bash
# Check if port 8000 is in use
lsof -i :8000

# Kill existing process if needed
pkill -f "php artisan serve"

# Restart server
php artisan serve --port=8000
```

## Next Steps

1. ✅ Visit `/modules` to see the database-connected page
2. ✅ Try adding/editing/deleting modules through the UI
3. ✅ Use API endpoints to create other entities
4. ✅ Create frontend pages for other entities (stages, formateurs, etc.)
5. ✅ Add authentication middleware when ready

## Support

For detailed API documentation, check `routes/api.php` for all available endpoints.

For database schema details, check `IMPLEMENTATION_SUMMARY.md`.

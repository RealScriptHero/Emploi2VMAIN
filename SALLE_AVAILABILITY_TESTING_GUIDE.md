# SALLE AVAILABILITY CHECKING - TESTING GUIDE

## Implementation Summary

The salle (classroom) availability checking system has been successfully implemented to prevent double-booking in Emploi du Temps. This system ensures that a salle can only be used by ONE formateur/group at a specific day and time slot.

### Files Modified

1. **Backend**
   - `app/Http/Controllers/SalleController.php` - Added `getAvailableSalles()` method
   - `app/Http/Controllers/EmploiDuTempsController.php` - Added validation to `saveForFormateurs()` and `saveGroupeTimetable()`
   - `routes/api.php` - Added `/api/salles/available` route

2. **Frontend**
   - `resources/views/emploi/formateur.blade.php` - Added availability checking logic
   - `resources/views/emploi/global.blade.php` - Added availability checking logic

## New API Endpoint

### GET /api/salles/available
**Parameters:**
- `jour` (required): Day of week (Lundi, Mardi, Mercredi, Jeudi, Vendredi, Samedi)
- `creneau` (required): Time slot (S1, S2, S3, S4)  
- `exclude_id` (optional): Current emploi ID when editing (to allow re-saving same salle)

**Response:**
```json
{
  "data": [
    { "id": 1, "nomSalle": "Salle A1", "centre_id": 1 },
    { "id": 3, "nomSalle": "Salle A3", "centre_id": 1 }
  ]
}
```

**Example Calls:**
```
GET /api/salles/available?jour=Lundi&creneau=S1
GET /api/salles/available?jour=Mardi&creneau=S2&exclude_id=123
```

## Testing Scenarios

### SCENARIO 1: Emploi Groupe - Basic Salle Blocking

**Steps:**
1. Go to **Emploi Groupe** page
2. Select a centre (e.g., "CFMPS")
3. Select a date (e.g., Monday Jan 15, 2026)
4. For Group DEV202:
   - Row "Formateur", Monday slot 1 (S1): Select "Benali"
   - Row "Module", Monday slot 1: Select "Python"
   - Row "Salle", Monday slot 1: Select "Salle A1"
5. Click **Save** → Should save successfully

**Verification:**
6. For Group NET101 (same centre):
   - Row "Formateur", Monday slot 1: Select "Ahmed"
   - Row "Module", Monday slot 1: Select "Networking"
   - Row "Salle", Monday slot 1: Click dropdown
   - **✅ EXPECTED**: "Salle A1" should NOT appear in the dropdown
   - **✅ Shows**: Only available salles (A2, A3, B1, etc.)

---

### SCENARIO 2: Emploi Groupe - Salle Available for Different Time Slot

**Prerequisites:** Continue from Scenario 1

**Steps:**
1. In Group NET101, Row "Salle", Monday slot 2 (S2): Click dropdown
2. **✅ EXPECTED**: "Salle A1" SHOULD appear in this dropdown (different time slot)
3. Select "Salle A1"
4. Click **Save** → Should save successfully

**Verification:**
5. Group DEV202, Monday slot 2: Salle dropdown should NOT show Salle A1

---

### SCENARIO 3: Emploi Formateur - Salle Blocking by Formateur

**Steps:**
1. Go to **Emploi Formateur** page  
2. Select a date (e.g., Monday Jan 15, 2026)
3. Find Formateur "Benali":
   - Groupe row, Monday S1: Select "DEV202"
   - Module row, Monday S1: Select "Python"
   - Salle row, Monday S1: Select "Salle B1"
4. Click **Save** → Should save successfully

**Verification:**
5. Find Formateur "Ahmed":
   - Groupe row, Monday S1: Select "NET101"
   - Module row, Monday S1: Select "Networking"  
   - Salle row, Monday S1: Click dropdown
   - **✅ EXPECTED**: "Salle B1" should NOT appear in dropdown
   - **✅ Shows**: Only available salles for Monday S1

---

### SCENARIO 4: Editing Entry - Salle Availability

**Prerequisites:** You have existing entries (from previous scenarios)

**Steps:**
1. Go to **Emploi Groupe** page
2. Group DEV202 has "Salle A1" on Monday S1
3. Change Monday S1 Module selection to different value
4. For Salle, Monday S1: Click dropdown
5. **✅ EXPECTED**: Should still show only available salles (excluding other occupied ones for Monday S1, but including A1 since you're editing the same entry)

**Note:** The `exclude_id` parameter in the API prevents your current entry from blocking itself.

---

### SCENARIO 5: Backend Validation - Conflict Prevention

**Steps (Using API directly via Postman or curl):**

```bash
# First, create entry with Salle A5 on Monday S1, Formateur 1, Group 1
POST /api/timetable-formateurs
{
  "date": "2026-01-13",
  "entries": [{
    "formateur_id": 1,
    "groupe_id": 1,
    "module_id": 1,
    "salle_id": 5,
    "jour": "Lundi",
    "creneau": "S1",
    "date": "2026-01-13"
  }]
}
```

**Response:** `200 OK - Timetable saved successfully`

```bash
# Now try to create another entry with same Salle A5 on Monday S1
POST /api/timetable-formateurs  
{
  "date": "2026-01-13",
  "entries": [{
    "formateur_id": 2,
    "groupe_id": 2,
    "module_id": 1,
    "salle_id": 5,
    "jour": "Lundi",
    "creneau": "S1",
    "date": "2026-01-13"
  }]
}
```

**✅ EXPECTED Response:**
- **Status:** `422 Unprocessable Entity`
- **Body:** 
```json
{
  "error": "Cette salle est déjà occupée à ce créneau horaire"
}
```

---

### SCENARIO 6: Warning on Changed Availability

**Steps:**
1. Go to **Emploi Groupe** page
2. Load a date with existing entries
3. Group A has "Salle X" on Monday S1
4. Switch to another group and select "Salle X" for Monday S1
5. Save successfully (different group, same salle, same time - should fail after save attempt)
6. Back to Group A, Monday S1 salle dropdown
7. **✅ EXPECTED**: 
   - If salle is now occupied: Warning message appears
   - Salle selection is cleared
   - Dropdown shows only available salles

---

### SCENARIO 7: All Salles Shown When No Time Selected  

**Steps:**
1. Go to **Emploi Groupe** page
2. Without selecting formateur/module, just look at Salle dropdown for any empty row
3. **✅ EXPECTED**: All salles should be shown (since no formateur/module = no time context)

---

## Browser Console Checks

While testing, open **Browser DevTools** (F12) and check the Console tab:

**Expected logs:**
```javascript
// When fetching available salles
// Should see successful fetch calls to: /api/salles/available?jour=...&creneau=...

// No errors like:
// - "Failed to fetch available salles"
// - "Cannot read properties of undefined"
```

**To check:**
1. Press F12 to open DevTools
2. Go to **Console** tab
3. No red error messages should appear during normal usage
4. Go to **Network** tab
5. Search for "salles/available" calls
6. Should see multiple successful GET requests (200 status) to `/api/salles/available`

---

## Expected Behavior Summary

| Scenario | Before Implementation | After Implementation |
|----------|----------------------|----------------------|
| Select salle for Monday S1 in one group | Other groups can select same salle Monday S1 | Other groups cannot select occupied salle |
| Change time from S1 to S2 | Salle appears in both | Salle only appears in S2 if not occupied |
| Try to save occupied salle via API | Entry saves (conflicts not checked) | 422 error returned |
| Dropdown population | All salles always shown | Only available salles shown |
| User experience | No visual feedback of conflicts | Warning if selection becomes unavailable |

---

## Fallback & Error Handling

**If API fails to fetch available salles:**
- All salles are shown (graceful degradation)
- Backend validation still prevents actual conflicts
- Error logged to browser console

**If user bypasses frontend via API:**
- Backend validation catches the conflict
- Returns clear error message: "Cette salle est déjà occupée à ce créneau horaire"

---

## Database Integrity

The system maintains data integrity through:
1. **Frontend validation**: Prevents user from selecting unavailable salles
2. **Backend validation**: Prevents API bypasses from causing conflicts
3. **Transaction handling**: Changes are rolled back if validation fails

---

## Performance Notes

- **Initial load**: All salles fetched once from `/api/salles`
- **Per cell update**: One API call to `/api/salles/available` per salle dropdown update
- **Async operations**: Non-blocking UI with async/await
- **Cached data**: Salles list reused across all cells

---

## Troubleshooting

### Salles always show as available
- Check: Is the API endpoint `/api/salles/available` accessible?
- Check browser console for fetch errors
- Verify database has employdi_du_temps entries for the date/time

### Dropdown shows empty options
- Check: Browser JavaScript console for errors
- Verify `fetchAvailableSalles()` method is defined
- Check network tab: Are API calls returning data?

### Save fails with 422 error
- Check: The salle is truly occupied for that jour/creneau
- Check: Are you trying to edit an entry? Use `exclude_id` parameter
- Check: Is `salle_id` being sent as null? Validation should allow null

### Salle dropdown doesn't update when formateur changes
- Check: `updateSalleDropdownForCell()` is called in onchange handlers
- Check: `refreshSalleDropdowns()` is called after renderTable()
- Check: JavaScript errors in browser console

---

## Rollback Instructions (if needed)

All changes are isolated to specific methods and can be reverted:

1. Remove `getAvailableSalles()` method from SalleController
2. Remove validation logic from `saveForFormateurs()` and `saveGroupeTimetable()`
3. Remove salle availability methods from blade.php files
4. Remove route from routes/api.php
5. Frontend will fall back to showing all salles

---

## Success Criteria

✅ All tests pass without errors
✅ Salle dropdowns are context-aware (show available salles only)
✅ API endpoint returns correct salles for day/time
✅ Validation prevents saving occupied salles
✅ User receives clear feedback when action is not possible
✅ Both Emploi Groupe and Emploi Formateur work correctly
✅ Editing existing entries allows re-saving same salle

---

**Testing Date:** April 1, 2026  
**Implementation Status:** ✅ COMPLETE

{{-- resources/views/seeker/cv/templates.blade.php --}}
{{-- Dynamic row templates for CV Form (Person B) --}}

{{-- 1. EDUCATION TEMPLATE --}}
<template id="education-template">
    <div class="row mt-3 dynamic-row border rounded p-3 mb-3 bg-light">
        <div class="col-md-3">
            <label class="form-label">Institution</label>
            <select name="educations[][institution_id]" class="form-control" required>
                <option value="">-- Select --</option>
                @foreach($institutions as $inst)
                    <option value="{{ $inst->id }}">{{ $inst->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Degree Level</label>
            <select name="educations[][degree_level_id]" class="form-control" required>
                <option value="">-- Select --</option>
                @foreach($degrees as $deg)
                    <option value="{{ $deg->id }}">{{ $deg->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Major</label>
            <select name="educations[][major_id]" class="form-control" required>
                <option value="">-- Select --</option>
                @foreach($majors as $major)
                    <option value="{{ $major->id }}">{{ $major->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Start Year</label>
            <select name="educations[][start_year]" class="form-control" required>
                <option value="">-- Year --</option>
                @for($y = 1990; $y <= 2026; $y++)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">End Year</label>
            <select name="educations[][end_year]" class="form-control">
                <option value="">-- Year --</option>
                @for($y = 1990; $y <= 2026; $y++)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="col-12 mt-2">
            <label class="form-label">Description (Optional)</label>
            <textarea name="educations[][description]" class="form-control" rows="2"></textarea>
        </div>
        <div class="col-12 text-end mt-2">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove Degree</button>
        </div>
    </div>
</template>

{{-- 2. WORK HISTORY TEMPLATE --}}
<template id="work-template">
    <div class="row mt-3 dynamic-row border rounded p-3 mb-3 bg-light">
        <div class="col-md-3">
            <label class="form-label">Company Name</label>
            <input type="text" name="work_histories[][company_name]" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Job Title</label>
            <select name="work_histories[][job_title_id]" class="form-control" required>
                <option value="">-- Select --</option>
                @foreach($jobTitles as $title)
                    <option value="{{ $title->id }}">{{ $title->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Employment Type</label>
            <select name="work_histories[][employment_type_id]" class="form-control" required>
                <option value="">-- Select --</option>
                @foreach($employmentTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Industry</label>
            <select name="work_histories[][industry_id]" class="form-control" required>
                <option value="">-- Select --</option>
                @foreach($industries as $ind)
                    <option value="{{ $ind->id }}">{{ $ind->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1">
            <label class="form-label">Start Year</label>
            <select name="work_histories[][start_year]" class="form-control" required>
                <option value="">-- Year --</option>
                @for($y = 1990; $y <= 2026; $y++)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-1">
            <label class="form-label">End Year</label>
            <select name="work_histories[][end_year]" class="form-control">
                <option value="Present">Present</option>
                @for($y = 1990; $y <= 2026; $y++)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="col-12 mt-2">
            <label class="form-label">Job Description</label>
            <textarea name="work_histories[][job_description]" class="form-control" rows="3"></textarea>
        </div>
        <div class="col-12 text-end mt-2">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove Work</button>
        </div>
    </div>
</template>

{{-- 3. CERTIFICATE TEMPLATE --}}
<template id="certificate-template">
    <div class="row mt-3 dynamic-row border rounded p-3 mb-3 bg-light">
        <div class="col-md-4">
            <label class="form-label">Certificate Name</label>
            <select name="certificates[][certificate_name_id]" class="form-control" required>
                <option value="">-- Select --</option>
                @foreach($certificates as $cert)
                    <option value="{{ $cert->id }}">{{ $cert->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Issuing Organization</label>
            <select name="certificates[][issuing_organization_id]" class="form-control" required>
                <option value="">-- Select --</option>
                @foreach($organizations as $org)
                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Year Issued</label>
            <select name="certificates[][year_issued]" class="form-control" required>
                <option value="">-- Year --</option>
                @for($y = 1990; $y <= 2026; $y++)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="col-12 mt-2">
            <label class="form-label">Description (Optional)</label>
            <textarea name="certificates[][description]" class="form-control" rows="2"></textarea>
        </div>
        <div class="col-12 text-end mt-2">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove Certificate</button>
        </div>
    </div>
</template>

{{-- 4. SKILL TEMPLATE --}}
<template id="skill-template">
    <div class="col-md-6 dynamic-row">
        <div class="input-group mb-2">
            <select name="skills[][skill_id]" class="form-control" required>
                <option value="">-- Select Skill --</option>
                @foreach($skillsList as $skill)
                    <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                @endforeach
            </select>
            <select name="skills[][proficiency_level_id]" class="form-control" required>
                <option value="">-- Proficiency --</option>
                @foreach($proficiencies as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-danger" onclick="removeRow(this)">×</button>
        </div>
    </div>
</template>
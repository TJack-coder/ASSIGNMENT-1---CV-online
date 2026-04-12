

-- Reference tables 
CREATE TABLE cv_categories (...);
CREATE TABLE countries (...);
CREATE TABLE cities (...);           -- can link to country_id
CREATE TABLE districts (...);        -- can link to city_id
CREATE TABLE degree_levels (...);
CREATE TABLE majors (...);
CREATE TABLE institutions (...);
CREATE TABLE job_titles (...);
CREATE TABLE employment_types (...);
CREATE TABLE industries (...);
CREATE TABLE skills (...);
CREATE TABLE certificate_names (...);
CREATE TABLE issuing_organizations (...);
CREATE TABLE proficiency_levels (...);   

-- Main CV table (ONE per job seeker)
CREATE TABLE cvs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,           -- enforces 1 CV per seeker
    cv_category_id INT NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('Male','Female','Other') NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    country_id INT NOT NULL,
    city_id INT NOT NULL,
    district_id INT NULL,
    street_address TEXT NOT NULL,
    postal_code VARCHAR(20) NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (cv_category_id) REFERENCES cv_categories(id)
);

-- Child tables (dynamic sections)
CREATE TABLE cv_educations (... cv_id INT NOT NULL ...);
CREATE TABLE cv_work_histories (... cv_id INT NOT NULL, company_name VARCHAR(255) NOT NULL ...);
CREATE TABLE cv_certificates (... cv_id INT NOT NULL ...);
CREATE TABLE cv_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cv_id INT NOT NULL,
    skill_id INT NOT NULL,
    proficiency_level_id INT NOT NULL,
    FOREIGN KEY (cv_id) REFERENCES cvs(id) ON DELETE CASCADE
);
# InfoSec Lab 2 - Secure Student Management System

## 📋 Overview

This is a comprehensive security-hardened Student Management System demonstrating proper security implementation across all OWASP Top 10 vulnerability categories.

## 🎯 Learning Objectives Achieved

✅ Identify insecure components in the system
✅ Detect database design issues and redundancy
✅ Identify data loss risks
✅ Detect missing encryption mechanisms
✅ Identify backup and recovery weaknesses
✅ Apply security improvements

---

## 🔐 Security Implementations

### 1. **Password Security (Authentication)**
- ✅ Using `password_hash()` with bcrypt algorithm
- ✅ Using `password_verify()` for login verification
- ✅ Password hash cost factor: 10
- Credentials: admin / admin123

### 2. **SQL Injection Prevention**
- ✅ All queries use prepared statements
- ✅ Parameter binding for all user inputs
- ✅ Eliminates all string concatenation in SQL

### 3. **Input Validation**
- ✅ Username validation (regex)
- ✅ Email validation (PHP filter)
- ✅ Student ID format validation
- ✅ Full name validation
- ✅ Duplicate key checking

### 4. **Output Sanitization (XSS Prevention)**
- ✅ All output uses `htmlspecialchars()`
- ✅ ENT_QUOTES flag for quote escaping
- ✅ UTF-8 character encoding specified

### 5. **Session Security**
- ✅ 30-minute session timeout
- ✅ IP address verification (session hijacking prevention)
- ✅ Secure session data structure
- ✅ Session regeneration on login
- ✅ HTTPS headers included

### 6. **Access Control (Authorization)**
- ✅ Role-based access control (RBAC)
- ✅ Admin-only operations verified
- ✅ User session validation on every request

### 7. **Database Normalization**
- ✅ Eliminated redundancy (courses separated)
- ✅ Foreign key relationships
- ✅ Proper indexing for performance
- ✅ Data integrity constraints

### 8. **Backup & Recovery Strategy**
- ✅ Daily automated backup plan
- ✅ Multiple backup tiers (daily, weekly, monthly)
- ✅ Off-site backup recommendations
- ✅ Recovery procedure documentation

### 9. **Additional Security**
- ✅ Comprehensive security headers
- ✅ Error handling without information disclosure
- ✅ Audit logging for compliance
- ✅ Secure password hashing utility

---

## 📁 File Structure

```
infosec_lab2-main/
├── db.php                    # Database connection with security headers
├── login.php                 # Secure login with prepared statements
├── dashboard.php             # Admin dashboard with output sanitization
├── add_student.php           # Student creation with input validation
├── delete_student.php        # Student deletion with access control
├── logout.php                # Session destruction
├── hash_password.php         # Password hashing utility (admin tool)
├── infosec_lab.sql          # Normalized database schema
├── style.css                 # Application styling
├── SECURITY_IMPROVEMENTS.md  # Detailed security documentation
└── README.md                 # This file
```

---

## 🚀 Setup Instructions

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or MariaDB 10.4+
- XAMPP or equivalent server environment

### Installation Steps

#### 1. Import Database Schema
```bash
# Access phpMyAdmin and create database
CREATE DATABASE infosec_lab;

# Import the SQL file
mysql -u root -p infosec_lab < infosec_lab.sql

# Or paste contents in phpMyAdmin SQL tab
```

#### 2. Place Files in Web Root
```bash
# Copy all PHP files to XAMPP htdocs
cp -r infosec_lab2-main/* C:\xampp\htdocs\infosec_lab2-main\
```

#### 3. Verify Installation
```
Access: http://localhost/infosec_lab2-main/login.php
```

#### 4. Login with Demo Credentials
- **Username**: admin
- **Password**: admin123

---

## 🔑 Demo Credentials

| Username | Password | Role |
|----------|----------|------|
| admin | admin123 | admin |

**Note**: Hashed password stored in database (bcrypt)

---

## 📋 Database Schema

### Users Table
```sql
CREATE TABLE `users` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `username` varchar(100) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,  -- bcrypt hash
  `role` enum('admin', 'user'),
  `is_active` tinyint DEFAULT 1,
  `created_at` timestamp,
  `last_login` timestamp NULL
);
```

### Students Table (Normalized)
```sql
CREATE TABLE `students` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `student_id` varchar(50) UNIQUE NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `course_id` int NOT NULL,
  FOREIGN KEY (`course_id`) REFERENCES `courses`(`course_id`)
);
```

### Courses Table (New - Eliminates Redundancy)
```sql
CREATE TABLE `courses` (
  `course_id` int PRIMARY KEY AUTO_INCREMENT,
  `course_code` varchar(50) UNIQUE NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `course_description` varchar(255)
);
```

### Audit Log Table (New - Security Audit Trail)
```sql
CREATE TABLE `audit_log` (
  `log_id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `action` varchar(100),
  `entity_type` varchar(50),
  `entity_id` int,
  `ip_address` varchar(45),
  `created_at` timestamp
);
```

---

## 🔍 Security Testing

### Test SQL Injection Protection
1. Login page: Try `' OR '1'='1` (should fail)
2. Add student: Try `'; DROP TABLE students;--` (should fail)
3. Delete: Try accessing `delete_student.php?id=1' OR '1'='1` (should fail)

**Result**: All attempts blocked by prepared statements ✅

### Test XSS Protection
1. Add student with name: `<script>alert('XSS')</script>`
2. View dashboard: Script should not execute, output escaped ✅

### Test Input Validation
1. Try student ID with special characters (should fail) ✅
2. Try email without @ symbol (should fail) ✅
3. Try duplicate student ID (should fail) ✅

### Test Session Security
1. Login and wait 30 minutes (session expires) ✅
2. Change IP address verification in browser (session hijacking blocked) ✅

### Test Access Control
1. Try accessing add_student.php as non-admin (access denied) ✅
2. Try accessing delete_student.php as non-admin (access denied) ✅

---

## 🛠️ Password Management

### Generate New Password Hash
1. Access: `http://localhost/infosec_lab2-main/hash_password.php`
2. Enter password to hash
3. Copy generated hash
4. Update database:
```sql
UPDATE users SET password = 'PASTED_HASH' WHERE username = 'admin';
```

### Password Requirements
- Minimum 8 characters
- Mix of uppercase, lowercase, numbers, special characters recommended
- Never store plain text passwords

---

## 📊 Key Improvements Summary

| Issue | Before | After | Status |
|-------|--------|-------|--------|
| **SQL Injection** | String concatenation | Prepared statements | ✅ FIXED |
| **Password Storage** | Plain text | Bcrypt hashed | ✅ FIXED |
| **Input Validation** | None | Comprehensive | ✅ FIXED |
| **Output Escaping** | Raw output | htmlspecialchars | ✅ FIXED |
| **Session Timeout** | No timeout | 30 minutes | ✅ FIXED |
| **Session Hijacking** | Not prevented | IP verification | ✅ FIXED |
| **Access Control** | No checks | Role-based RBAC | ✅ FIXED |
| **Data Redundancy** | Multiple courses per student | Normalized schema | ✅ FIXED |
| **Backup Strategy** | None documented | Documented & implementable | ✅ FIXED |
| **Error Handling** | Technical details | Generic messages | ✅ FIXED |
| **Audit Trail** | Not available | Logged to database | ✅ ADDED |
| **Security Headers** | Missing | Comprehensive set | ✅ ADDED |

---

## 🔒 Security Best Practices Applied

1. **Defense in Depth**: Multiple layers of security
2. **Principle of Least Privilege**: Users have minimal required permissions
3. **Input Validation**: Whitelist approach
4. **Output Sanitization**: Always escape user data
5. **Secure Defaults**: HTTPS headers, security settings enabled
6. **Fail Securely**: Generic error messages to users
7. **Separation of Concerns**: Database, logic, presentation layers
8. **Logging & Monitoring**: Audit trail for all critical actions

---

## 📚 Documentation Files

- **SECURITY_IMPROVEMENTS.md**: Detailed security analysis and fixes
- **README.md**: This file - setup and usage guide
- **infosec_lab.sql**: Database schema with comments

---

## 🚨 Important Security Notes

⚠️ **Before Production Deployment**:

1. [ ] Update database credentials
2. [ ] Generate new admin password
3. [ ] Enable HTTPS (SSL/TLS)
4. [ ] Set up automated backups
5. [ ] Configure firewall rules
6. [ ] Remove hash_password.php from public access
7. [ ] Set proper file permissions (644 for files, 755 for directories)
8. [ ] Enable error logging to secure location
9. [ ] Regular security updates for PHP and MySQL
10. [ ] Conduct penetration testing

⚠️ **Never**:
- Commit passwords to version control
- Store credentials in config files
- Expose database credentials in code
- Use plain HTTP in production
- Skip security updates
- Disable error logging

---

## 📞 Support & Troubleshooting

### Common Issues

**"Connection failed"**
- Check MySQL is running
- Verify database credentials in db.php
- Ensure database 'infosec_lab' exists

**"Session expired"**
- Normal behavior after 30 minutes of inactivity
- Log in again to continue

**"Access Denied"**
- Only admin users can add/delete students
- Change role in database if needed

**"SQL Errors"**
- Check if database schema was imported correctly
- Verify foreign key relationships

---

## 📖 Learning Resources

### Related Topics
- OWASP Top 10 Vulnerabilities
- MySQL Prepared Statements
- PHP Security Best Practices
- Password Hashing Algorithms (bcrypt)
- Session Management
- Database Normalization

### External Resources
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP Security Guide](https://www.php.net/manual/en/security.php)
- [MySQL Prepared Statements](https://dev.mysql.com/doc/)
- [bcrypt Documentation](https://en.wikipedia.org/wiki/Bcrypt)

---

## ✅ Assessment Checklist

Students should verify:

- [ ] SQL injection vulnerabilities are eliminated
- [ ] Passwords are hashed with bcrypt
- [ ] All inputs are validated
- [ ] All outputs are sanitized
- [ ] Session management is secure
- [ ] Access control is enforced
- [ ] Database is normalized
- [ ] Backup strategy is documented
- [ ] Audit logging is implemented
- [ ] Error handling is secure

---

## 📝 Notes for Educators

This lab demonstrates:
1. Real-world security vulnerabilities
2. Industry-standard fixes and best practices
3. Secure coding patterns
4. Database design for security
5. Compliance requirements (audit trails)

**Lab Duration**: 2-3 hours
**Difficulty**: Intermediate to Advanced
**Prerequisites**: PHP, MySQL, Web Security concepts

---

**Last Updated**: May 7, 2026
**Version**: 2.0 - Fully Secured
**Status**: ✅ All security improvements implemented and tested

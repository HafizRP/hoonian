# 📚 Hoonian Documentation Index

Panduan lengkap untuk semua aspek Hoonian - dari setup hingga production deployment.

## 🚀 Getting Started

### Untuk Pemula
1. **[QUICKSTART.md](QUICKSTART.md)** ⚡
   - Setup dalam 5 menit
   - Perintah dasar
   - Quick reference

2. **[CHEATSHEET.md](CHEATSHEET.md)** 📋
   - Command cheat sheet
   - One-liners
   - Quick fixes

### Untuk Deployment
3. **[SERVER_DEPLOYMENT.md](SERVER_DEPLOYMENT.md)** ⭐ **RECOMMENDED**
   - Panduan lengkap deployment di server (Bahasa Indonesia)
   - Step-by-step dari upload hingga running
   - Troubleshooting lengkap
   - Backup & monitoring

4. **[QUICK_DEPLOY.md](QUICK_DEPLOY.md)** 🚀
   - Quick deployment guide
   - Minimal steps
   - Server setup

## 🐳 Docker & Infrastructure

5. **[DOCKER_DEPLOYMENT.md](DOCKER_DEPLOYMENT.md)** 🐳
   - Docker architecture
   - Container management
   - Production deployment
   - Security best practices

6. **[EXTERNAL_DATABASE.md](EXTERNAL_DATABASE.md)** 🗄️
   - Setup external database
   - Migration guide
   - Connection configuration

## 🔧 Troubleshooting & Maintenance

7. **[TROUBLESHOOTING.md](TROUBLESHOOTING.md)** 🔧
   - Common issues & solutions
   - Debugging tools
   - Emergency recovery
   - Health checks

8. **[DEPLOYMENT_FIXES.md](DEPLOYMENT_FIXES.md)** ✅
   - Summary of improvements
   - What's been fixed
   - Testing checklist

## 📄 Features & Systems

9. **[INVOICE_SYSTEM.md](INVOICE_SYSTEM.md)** 📄
   - Invoice generation
   - PDF export
   - Tax calculation
   - Payment tracking

10. **[README.md](README.md)** 📖
    - Project overview
    - Features
    - Tech stack
    - Installation

## 📊 Documentation Map

```
┌─────────────────────────────────────────────────┐
│         MULAI DI SINI (START HERE)              │
├─────────────────────────────────────────────────┤
│                                                 │
│  Baru pertama kali?                             │
│  └─> QUICKSTART.md (5 menit)                    │
│                                                 │
│  Mau deploy ke server?                          │
│  └─> SERVER_DEPLOYMENT.md ⭐                     │
│                                                 │
│  Butuh command cepat?                           │
│  └─> CHEATSHEET.md                              │
│                                                 │
│  Ada masalah?                                   │
│  └─> TROUBLESHOOTING.md                         │
│                                                 │
└─────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────┐
│         DETAIL DOCUMENTATION                    │
├─────────────────────────────────────────────────┤
│                                                 │
│  Docker Details                                 │
│  └─> DOCKER_DEPLOYMENT.md                       │
│                                                 │
│  External Database                              │
│  └─> EXTERNAL_DATABASE.md                       │
│                                                 │
│  Invoice System                                 │
│  └─> INVOICE_SYSTEM.md                          │
│                                                 │
│  What's Fixed                                   │
│  └─> DEPLOYMENT_FIXES.md                        │
│                                                 │
└─────────────────────────────────────────────────┘
```

## 🎯 Use Cases

### "Saya mau setup lokal untuk development"
1. Baca: [QUICKSTART.md](QUICKSTART.md)
2. Run: `./docker.sh setup`
3. Reference: [CHEATSHEET.md](CHEATSHEET.md)

### "Saya mau deploy ke server production"
1. Baca: [SERVER_DEPLOYMENT.md](SERVER_DEPLOYMENT.md) ⭐
2. Follow step-by-step
3. Jika ada masalah: [TROUBLESHOOTING.md](TROUBLESHOOTING.md)

### "Container saya error"
1. Cek: [TROUBLESHOOTING.md](TROUBLESHOOTING.md)
2. Lihat logs: `./docker.sh logs app`
3. Rebuild: `./docker.sh rebuild`

### "Saya mau pakai database yang sudah ada"
1. Baca: [EXTERNAL_DATABASE.md](EXTERNAL_DATABASE.md)
2. Update `.env`: `DB_HOST=host.docker.internal`
3. Restart: `./docker.sh restart`

### "Saya mau generate invoice"
1. Baca: [INVOICE_SYSTEM.md](INVOICE_SYSTEM.md)
2. Login ke backoffice
3. Go to Transactions → Generate Invoice

### "Saya lupa command Docker"
1. Quick: `./docker.sh help`
2. Detail: [CHEATSHEET.md](CHEATSHEET.md)

## 📱 Quick Commands

```bash
# Setup
./docker.sh setup

# Start/Stop
./docker.sh start
./docker.sh stop

# Logs
./docker.sh logs app

# Shell
./docker.sh shell

# Help
./docker.sh help
```

## 🔗 External Resources

- **Laravel Docs**: https://laravel.com/docs
- **Docker Docs**: https://docs.docker.com
- **DomPDF**: https://github.com/barryvdh/laravel-dompdf
- **Bootstrap**: https://getbootstrap.com

## 📞 Support

### Dokumentasi
- Semua file `.md` di root project
- Inline comments di code
- `./docker.sh help`

### Troubleshooting
1. Check [TROUBLESHOOTING.md](TROUBLESHOOTING.md)
2. Check logs: `./docker.sh logs`
3. Check status: `./docker.sh status`

### Community
- Create issue di repository
- Email: support@hoonian.com

## ✅ Checklist

### Development Setup
- [ ] Baca `QUICKSTART.md`
- [ ] Copy `.env.example` ke `.env`
- [ ] Run `./docker.sh setup`
- [ ] Test: http://localhost:8004
- [ ] Bookmark `CHEATSHEET.md`

### Production Deployment
- [ ] Baca `SERVER_DEPLOYMENT.md` ⭐
- [ ] Setup server & upload project
- [ ] Configure `.env` untuk production
- [ ] Run `./docker.sh setup`
- [ ] Test semua fitur
- [ ] Setup backup (lihat `SERVER_DEPLOYMENT.md`)
- [ ] Setup monitoring
- [ ] Setup SSL/TLS

### Post-Deployment
- [ ] Change default passwords
- [ ] Test invoice generation
- [ ] Test file uploads
- [ ] Test Google OAuth (if used)
- [ ] Setup automated backups
- [ ] Monitor logs

## 🎓 Learning Path

### Beginner
1. `QUICKSTART.md` - Mulai di sini
2. `CHEATSHEET.md` - Command reference
3. `README.md` - Understand features

### Intermediate
4. `DOCKER_DEPLOYMENT.md` - Docker details
5. `INVOICE_SYSTEM.md` - Invoice system
6. `TROUBLESHOOTING.md` - Problem solving

### Advanced
7. `SERVER_DEPLOYMENT.md` - Production deployment
8. `EXTERNAL_DATABASE.md` - Advanced DB setup
9. `DEPLOYMENT_FIXES.md` - Technical improvements

## 📊 Documentation Stats

| File | Lines | Purpose | Audience |
|------|-------|---------|----------|
| QUICKSTART.md | ~100 | Quick start | Beginner |
| CHEATSHEET.md | ~300 | Command reference | All |
| SERVER_DEPLOYMENT.md | ~500 | Full deployment | DevOps |
| TROUBLESHOOTING.md | ~400 | Problem solving | All |
| DOCKER_DEPLOYMENT.md | ~380 | Docker details | Advanced |
| INVOICE_SYSTEM.md | ~200 | Invoice feature | Developer |
| EXTERNAL_DATABASE.md | ~150 | DB setup | DevOps |
| README.md | ~280 | Overview | All |

## 🔄 Keep Updated

Documentation ini akan terus di-update. Check:
- Git commits untuk changes
- `DEPLOYMENT_FIXES.md` untuk latest improvements
- README.md untuk feature updates

---

**Happy Coding! 🚀**

Last Updated: 2025-12-20

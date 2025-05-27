const express = require('express');
const multer = require('multer');
const File = require('../models/File');

const router = express.Router();

const storage = multer.diskStorage({
  destination: 'uploads/',
  filename: (req, file, cb) => cb(null, Date.now() + '-' + file.originalname)
});
const upload = multer({ storage });

// Middleware de autenticação
function auth(req, res, next) {
  if (!req.session.userId) return res.status(401).send('Não autorizado');
  next();
}

// Upload
router.post('/upload', auth, upload.single('arquivo'), async (req, res) => {
  const file = new File({
    nome: req.file.originalname,
    path: req.file.path,
    mimetype: req.file.mimetype,
    uploadedBy: req.session.userId
  });
  await file.save();
  res.send('Arquivo salvo!');
});

// Listar
router.get('/files', auth, async (req, res) => {
  const files = await File.find({ uploadedBy: req.session.userId });
  res.json(files);
});

// Download
router.get('/download/:id', auth, async (req, res) => {
  const file = await File.findById(req.params.id);
  res.download(file.path, file.nome);
});

module.exports = router;


// Legacy translations file - kept for backward compatibility
// New translations are in src/i18n/locales/*.json

type Language = 'es' | 'en' | 'pt';

export const translations: Record<Language, any> = {
  es: {
    nav: {
      home: "Inicio",
      downloads: "Descargas",
      info: "Información",
      ranking: "Ranking",
      donate: "Donaciones",
      back: "Volver"
    },
    streams: {
      title: "Transmisiones",
      subtitle: "En Vivo Ahora",
      watch: "Ver Stream"
    },
    uniqueFeatures: {
      title: "Novedades Exclusivas",
      skinsTitle: "Skins Personalizados",
      skinsDesc: "Descubre nuestra colección única de apariencias.",
      animTitle: "Animaciones de Personajes",
      animDesc: "Nuevos movimientos fluidos y habilidades reelaboradas.",
      viewGallery: "VER GALERÍA",
      viewAnim: "VER DEMO"
    },
    skins: {
      title: "Galería de Skins",
      subtitle: "Haz clic para ver la vista previa en video"
    },
    animations: {
      title: "Animaciones por Raza",
      subtitle: "Selecciona una raza para ver sus nuevas animaciones de combate",
      races: {
        human: "Humanos",
        elf: "Elfos de Luz",
        darkElf: "Elfos Oscuros",
        orc: "Orcos",
        dwarf: "Enanos"
      }
    },
    register: {
        title: "Crear Cuenta",
        subtitle: "Registra tu cuenta para jugar",
        username: "Usuario",
        email: "Email",
        confirmEmail: "Confirmar Email",
        password: "Contraseña",
        confirmPassword: "Confirmar Contraseña",
        create: "Crear Cuenta",
        creating: "Creando...",
        cancel: "Cancelar",
        usernameHint: "4-14 caracteres alfanuméricos",
        fillAll: "Por favor completa todos los campos",
        emailMismatch: "Los emails no coinciden",
        passMismatch: "Las contraseñas no coinciden",
        loginLength: "El usuario debe tener entre 4 y 14 caracteres",
        alphanumeric: "Solo caracteres alfanuméricos permitidos",
        success: "Cuenta creada exitosamente",
        error: "Error al crear cuenta",
        connectionError: "Error de conexión"
    },
    recover: {
        title: "Recuperar Contraseña",
        subtitle: "Ingresa tu email para recuperar tu cuenta",
        email: "Email",
        send: "Enviar Email",
        sending: "Enviando...",
        cancel: "Cancelar",
        hint: "Recibirás un email con instrucciones para recuperar tu cuenta",
        fillEmail: "Por favor ingresa tu email",
        invalidEmail: "Email inválido",
        success: "Email de recuperación enviado. Revisa tu bandeja de entrada.",
        error: "Error al enviar email",
        connectionError: "Error de conexión"
    },
    home: {
        welcome: "Bienvenido",
        desc: "Servidor Lineage 2 Interlude x10 - Dificultad Difícil",
        newsTitle: "Noticias del Servidor",
        readMore: "Leer más"
    },
    features: {
      title: "Información del Servidor",
      general: {
        title: "General",
        chronicle: "Crónica: Interlude",
        rates: "Rates: EXP/SP/Adena x10",
        level: "Nivel Inicial: 1",
        difficulty: "Dificultad: Difícil",
        autofarm: "Auto Farm: No",
        buffs: "Buffs: 1 Hora",
        slots: "Slots: 24 + 4 (Divine Inspiration Free)",
        noblesse: "Nobleza: Quest o Donate Coin"
      },
      premium: {
        title: "Premium / Boosts",
        items: [
            "Drop: 1.5x",
            "Adena: 1.5x",
            "EXP/SP: 1.5x"
        ]
      },
      enchant: {
        title: "Encantamiento",
        safe: "Safe: +3",
        max: "Max: +20",
        blessed: "Blessed: +3% chance (Fallo -> +3)",
        gae: "Blessed GAE: +3% chance vs Blessed (Fallo -> +4)",
        augmentChance: "Chance Augment: 5%",
        augmentMax: "1 Activo o 1 Pasivo (Sin acumulables)"
      },
      equipment: {
        title: "Equipamiento",
        gradeC: "Grado C: Adena",
        gradeAS: "Grado A-S: Craft o Donate Coin",
        bossJewels: "Joyas Boss: Farm en Raid Boss",
        tattoos: "Tatuajes: Max Grado C (Skins disponibles)"
      },
      clan: {
        title: "Sistema de Clan",
        maxMembers: "Miembros Max: 40",
        raidLimit: "Límite RB Clan/Ali: 2 Partys",
        epicLimit: "Límite Epic Clan/Ali: 2 Partys"
      },
      olympiad: {
        title: "Olimpiadas",
        time: "Horario: 18:00 - 00:00 (GMT-3)",
        maxEnchant: "Max Enchant: +10",
        cycle: "Ciclo: 14 Días",
        jewels: "Joyas Boss permitidas"
      },
      events: {
        title: "Eventos",
        tvt: "TvT en 8 mapas distintos",
        pvp: "Evento PvP cada 1 hora",
        reward: "Premio Top PvP: 1 Piedra de Piel"
      },
      security: {
        title: "Seguridad",
        clients: "Max Clientes por PC: 2",
        ddos: "Protección DDoS Activa"
      }
    },
    ranking: {
        title: "Ranking de Olimpiadas",
        subtitle: "Héroes actuales y puntuaciones del ciclo",
        class: "Clase",
        player: "Jugador",
        points: "Puntos",
        status: "Estado"
    },
    downloads: {
      title: "Descargas",
      subtitle: "Cliente Interlude Completo",
      clientTitle: "Cliente Completo",
      clientDesc: "Contiene el juego completo + sistema. No requiere parches adicionales.",
      instructions: "Instrucciones de Instalación:",
      step1: "1. Descarga el cliente desde cualquiera de las opciones.",
      step2: "2. Descomprime el archivo y ejecuta /system/l2.exe",
      troubleshooting: "Solución de Problemas",
      troubleDesc: "¿Problemas al entrar? Mira este video tutorial con soluciones comunes."
    },
    donate: {
      title: "Donaciones",
      subtitle: "Apoya al servidor y obtén Donate Coins",
      method: "Método: Enviar comprobante al GM por Discord.",
      items: "Tienda de Donación: Piedras de Piel, Level Up, Karma, Cambio de Nombre, Color de Nombre, Armas/Armaduras AYS.",
      form: {
        charName: "Nombre del Personaje",
        currency: "Moneda",
        amount: "Monto a Pagar",
        receive: "Recibes (Donate Coins)",
        payMp: "Pagar con Mercado Pago",
        payPp: "Pagar con PayPal",
        payPrex: "Pagar con Prex",
        fillAll: "Por favor completa todos los campos"
      },
      conversion: {
        ars: "Pesos Argentinos (ARS)",
        usd: "Dólares (USD)",
        clp: "Pesos Chilenos (CLP)"
      },
      table: {
        ars: [
            { amount: "15.000", coins: "1.000" },
            { amount: "30.000", coins: "2.200" },
            { amount: "45.000", coins: "3.500" }
        ],
        usd: [
            { amount: "10", coins: "1.000" },
            { amount: "15", coins: "1.550" },
            { amount: "20", coins: "2.200" },
            { amount: "30", coins: "3.500" },
            { amount: "40", coins: "4.800" },
            { amount: "50", coins: "6.000" },
            { amount: "100", coins: "12.500" },
            { amount: "150", coins: "19.500" },
            { amount: "200", coins: "27.000" },
            { amount: "250", coins: "35.000" },
            { amount: "300", coins: "44.000" }
        ],
        clp: [
            { amount: "10.000", coins: "1.000" },
            { amount: "15.000", coins: "1.550" },
            { amount: "20.000", coins: "2.200" },
            { amount: "30.000", coins: "3.500" },
            { amount: "40.000", coins: "4.800" },
            { amount: "50.000", coins: "6.000" },
            { amount: "100.000", coins: "12.500" },
            { amount: "150.000", coins: "19.500" },
            { amount: "200.000", coins: "27.000" },
            { amount: "250.000", coins: "35.000" },
            { amount: "300.000", coins: "44.000" }
        ]
      }
    },
    sidebar: {
      status: "Estado del Servidor",
      online: "Online",
      players: "Jugadores",
      vote: "Votar",
      login: "Panel de Usuario",
      user: "Usuario",
      pass: "Contraseña",
      enter: "ENTRAR",
      admin: "Admin Login",
      register: "¡Regístrate Ahora!"
    },
    footer: "© 2024 [SERVER_NAME]. Lineage II Interlude Server."
  },
  en: {
    nav: {
      home: "Home",
      downloads: "Downloads",
      info: "Info",
      ranking: "Ranking",
      donate: "Donate",
      back: "Back"
    },
    streams: {
      title: "Live Streams",
      subtitle: "Live Now",
      watch: "Watch Stream"
    },
    uniqueFeatures: {
      title: "Exclusive Features",
      skinsTitle: "Custom Skins",
      skinsDesc: "Check out our unique collection of weapon and armor skins.",
      animTitle: "Character Animations",
      animDesc: "Revamped fluid and realistic movements for all classes.",
      viewGallery: "VIEW GALLERY",
      viewAnim: "VIEW DEMO"
    },
    skins: {
      title: "Skins Gallery",
      subtitle: "Click to preview video"
    },
    animations: {
      title: "Race Animations",
      subtitle: "Select a race to preview new combat animations",
      races: {
        human: "Humans",
        elf: "Light Elves",
        darkElf: "Dark Elves",
        orc: "Orcs",
        dwarf: "Dwarves"
      }
    },
    register: {
        title: "Create Account",
        subtitle: "Register your account to play",
        username: "Username",
        email: "Email",
        confirmEmail: "Confirm Email",
        password: "Password",
        confirmPassword: "Confirm Password",
        create: "Create Account",
        creating: "Creating...",
        cancel: "Cancel",
        usernameHint: "4-14 alphanumeric characters",
        fillAll: "Please fill all fields",
        emailMismatch: "Emails do not match",
        passMismatch: "Passwords do not match",
        loginLength: "Username must be between 4 and 14 characters",
        alphanumeric: "Only alphanumeric characters allowed",
        success: "Account created successfully",
        error: "Error creating account",
        connectionError: "Connection error"
    },
    recover: {
        title: "Recover Password",
        subtitle: "Enter your email to recover your account",
        email: "Email",
        send: "Send Email",
        sending: "Sending...",
        cancel: "Cancel",
        hint: "You will receive an email with instructions to recover your account",
        fillEmail: "Please enter your email",
        invalidEmail: "Invalid email",
        success: "Recovery email sent. Check your inbox.",
        error: "Error sending email",
        connectionError: "Connection error"
    },
    home: {
        welcome: "Welcome",
        desc: "Lineage 2 Interlude Server x10 - Hard Difficulty",
        newsTitle: "Server News",
        readMore: "Read More"
    },
    features: {
      title: "Server Information",
      general: {
        title: "General",
        chronicle: "Chronicle: Interlude",
        rates: "Rates: EXP/SP/Adena x10",
        level: "Starting Level: 1",
        difficulty: "Difficulty: Hard",
        autofarm: "Auto Farm: No",
        buffs: "Buffs: 1 Hour",
        slots: "Slots: 24 + 4 (Divine Inspiration Free)",
        noblesse: "Noblesse: Quest or Donate Coin"
      },
      premium: {
        title: "Premium / Boosts",
        items: [
            "Drop: 1.5x",
            "Adena: 1.5x",
            "EXP/SP: 1.5x"
        ]
      },
      enchant: {
        title: "Enchant",
        safe: "Safe: +3",
        max: "Max: +20",
        blessed: "Blessed: +3% chance (Fail -> +3)",
        gae: "Blessed GAE: +3% chance vs Blessed (Fail -> +4)",
        augmentChance: "Augment Chance: 5%",
        augmentMax: "1 Active or 1 Passive (No stacking)"
      },
      equipment: {
        title: "Equipment",
        gradeC: "Grade C: Adena",
        gradeAS: "Grade A-S: Craft or Donate Coin",
        bossJewels: "Boss Jewels: Raid Boss Farm",
        tattoos: "Tattoos: Max Grade C (Skins available)"
      },
      clan: {
        title: "Clan System",
        maxMembers: "Max Members: 40",
        raidLimit: "RB Limit Clan/Ally: 2 Parties",
        epicLimit: "Epic Limit Clan/Ally: 2 Parties"
      },
      olympiad: {
        title: "Olympiad",
        time: "Time: 18:00 - 00:00 (GMT-3)",
        maxEnchant: "Max Enchant: +10",
        cycle: "Cycle: 14 Days",
        jewels: "Boss Jewels Allowed"
      },
      events: {
        title: "Events",
        tvt: "TvT in 8 different maps",
        pvp: "PvP Event every 1 hour",
        reward: "Top PvP Reward: 1 Skin Stone"
      },
      security: {
        title: "Security",
        clients: "Max Clients per PC: 2",
        ddos: "DDoS Protection Active"
      }
    },
    ranking: {
        title: "Olympiad Ranking",
        subtitle: "Current Heroes and Cycle Points",
        class: "Class",
        player: "Player",
        points: "Points",
        status: "Status"
    },
    downloads: {
      title: "Downloads",
      subtitle: "Full Interlude Client Ready to Play",
      clientTitle: "Full Client",
      clientDesc: "Contains full game + system. No extra patches required.",
      instructions: "Installation Instructions:",
      step1: "1. Download the client from one of the mirrors below.",
      step2: "2. Unzip the archive and run /system/l2.exe",
      troubleshooting: "Troubleshooting",
      troubleDesc: "Issues connecting? Watch this tutorial video for common fixes."
    },
    donate: {
      title: "Donations",
      subtitle: "Support the server and get Donate Coins",
      method: "Method: Send receipt to GM via Discord.",
      items: "Donation Shop: Skin Stones, Level Up, Karma, Name Change, Name Color, AYS Weapons/Armors.",
      form: {
        charName: "Character Name",
        currency: "Currency",
        amount: "Payment Amount",
        receive: "You Receive (Donate Coins)",
        payMp: "Pay with Mercado Pago",
        payPp: "Pay with PayPal",
        payPrex: "Pay with Prex",
        fillAll: "Please fill all fields"
      },
      conversion: {
        ars: "Argentine Pesos (ARS)",
        usd: "US Dollars (USD)",
        clp: "Chilean Pesos (CLP)"
      },
      table: {
        ars: [
            { amount: "15,000", coins: "1,000" },
            { amount: "30,000", coins: "2,200" },
            { amount: "45,000", coins: "3,500" }
        ],
        usd: [
            { amount: "10", coins: "1,000" },
            { amount: "15", coins: "1,550" },
            { amount: "20", coins: "2,200" },
            { amount: "30", coins: "3,500" },
            { amount: "40", coins: "4,800" },
            { amount: "50", coins: "6,000" },
            { amount: "100", coins: "12,500" },
            { amount: "150", coins: "19,500" },
            { amount: "200", coins: "27,000" },
            { amount: "250", coins: "35,000" },
            { amount: "300", coins: "44,000" }
        ],
        clp: [
            { amount: "10,000", coins: "1,000" },
            { amount: "15,000", coins: "1,550" },
            { amount: "20,000", coins: "2,200" },
            { amount: "30,000", coins: "3.500" },
            { amount: "40,000", coins: "4,800" },
            { amount: "50,000", coins: "6,000" },
            { amount: "100,000", coins: "12,500" },
            { amount: "150,000", coins: "19,500" },
            { amount: "200,000", coins: "27,000" },
            { amount: "250.000", coins: "35,000" },
            { amount: "300,000", coins: "44.000" }
        ]
      }
    },
    sidebar: {
      status: "Server Status",
      online: "Online",
      players: "Players",
      vote: "Vote",
      login: "User Panel",
      user: "Username",
      pass: "Password",
      enter: "ENTER",
      admin: "Admin Login",
      register: "Register Now!"
    },
    footer: "© 2024 [SERVER_NAME]. Lineage II Interlude Server."
  },
  pt: {
    nav: {
      home: "Início",
      downloads: "Downloads",
      info: "Info",
      ranking: "Ranking",
      donate: "Doações",
      back: "Voltar"
    },
    streams: {
      title: "Streams ao Vivo",
      subtitle: "Online Agora",
      watch: "Assistir"
    },
    uniqueFeatures: {
      title: "Recursos Exclusivos",
      skinsTitle: "Skins Personalizadas",
      skinsDesc: "Confira nossa coleção única de aparências.",
      animTitle: "Animações de Personagens",
      animDesc: "Novos movimentos fluidos e habilidades reformuladas.",
      viewGallery: "VER GALERIA",
      viewAnim: "VER DEMO"
    },
    skins: {
      title: "Galeria de Skins",
      subtitle: "Clique para pré-visualizar o vídeo"
    },
    animations: {
      title: "Animações de Raça",
      subtitle: "Selecione uma raça para pré-visualizar novas animações de combate",
      races: {
        human: "Humanos",
        elf: "Elfos de Luz",
        darkElf: "Elfos Escuros",
        orc: "Orcs",
        dwarf: "Anões"
      }
    },
    register: {
        title: "Criar Conta",
        subtitle: "Registre sua conta para jogar",
        username: "Usuário",
        email: "E-mail",
        confirmEmail: "Confirmar E-mail",
        password: "Senha",
        confirmPassword: "Confirmar Senha",
        create: "Criar Conta",
        creating: "Criando...",
        cancel: "Cancelar",
        usernameHint: "4-14 caracteres alfanuméricos",
        fillAll: "Por favor preencha todos os campos",
        emailMismatch: "Os e-mails não coincidem",
        passMismatch: "As senhas não coincidem",
        loginLength: "O usuário deve ter entre 4 e 14 caracteres",
        alphanumeric: "Apenas caracteres alfanuméricos permitidos",
        success: "Conta criada com sucesso",
        error: "Erro ao criar conta",
        connectionError: "Erro de conexão"
    },
    recover: {
        title: "Recuperar Senha",
        subtitle: "Digite seu e-mail para recuperar sua conta",
        email: "E-mail",
        send: "Enviar E-mail",
        sending: "Enviando...",
        cancel: "Cancelar",
        hint: "Você receberá um e-mail com instruções para recuperar sua conta",
        fillEmail: "Por favor digite seu e-mail",
        invalidEmail: "E-mail inválido",
        success: "E-mail de recuperação enviado. Verifique sua caixa de entrada.",
        error: "Erro ao enviar e-mail",
        connectionError: "Erro de conexão"
    },
    home: {
        welcome: "Bem-vindo",
        desc: "Servidor Lineage 2 Interlude x10 - Dificuldade Difícil",
        newsTitle: "Notícias do Servidor",
        readMore: "Leia Mais"
    },
    features: {
      title: "Informações do Servidor",
      general: {
        title: "Geral",
        chronicle: "Crônica: Interlude",
        rates: "Rates: EXP/SP/Adena x10",
        level: "Nível Inicial: 1",
        difficulty: "Dificuldade: Difícil",
        autofarm: "Auto Farm: Não",
        buffs: "Buffs: 1 Hora",
        slots: "Slots: 24 + 4 (Inspiração Divina Grátis)",
        noblesse: "Nobreza: Quest ou Donate Coin"
      },
      premium: {
        title: "Premium / Boosts",
        items: [
            "Drop: 1.5x",
            "Adena: 1.5x",
            "EXP/SP: 1.5x"
        ]
      },
      enchant: {
        title: "Enchant",
        safe: "Safe: +3",
        max: "Max: +20",
        blessed: "Blessed: +3% chance (Falha -> +3)",
        gae: "Blessed GAE: +3% chance vs Blessed (Falha -> +4)",
        augmentChance: "Chance Augment: 5%",
        augmentMax: "1 Ativo ou 1 Passivo (Sem acumular)"
      },
      equipment: {
        title: "Equipamento",
        gradeC: "Grade C: Adena",
        gradeAS: "Grade A-S: Craft o Donate Coin",
        bossJewels: "Boss Jewels: Farm em Raid Boss",
        tattoos: "Tattoos: Max Grade C (Skins disponíveis)"
      },
      clan: {
        title: "Sistema de Clan",
        maxMembers: "Max Membros: 40",
        raidLimit: "Limite RB Clan/Ally: 2 Parties",
        epicLimit: "Limite Epic Clan/Ally: 2 Parties"
      },
      olympiad: {
        title: "Olimpíadas",
        time: "Horário: 18:00 - 00:00 (GMT-3)",
        maxEnchant: "Max Enchant: +10",
        cycle: "Ciclo: 14 Dias",
        jewels: "Boss Jewels Permitidas"
      },
      events: {
        title: "Eventos",
        tvt: "TvT em 8 mapas diferentes",
        pvp: "Evento PvP a cada 1 hora",
        reward: "Prêmio Top PvP: 1 Pedra de Skin"
      },
      security: {
        title: "Segurança",
        clients: "Max Clientes por PC: 2",
        ddos: "Proteção DDoS Ativa"
      }
    },
    ranking: {
        title: "Ranking das Olimpíadas",
        subtitle: "Heróis atuais e pontuações do ciclo",
        class: "Classe",
        player: "Jogador",
        points: "Pontos",
        status: "Status"
    },
    downloads: {
      title: "Downloads",
      subtitle: "Cliente Interlude Completo",
      clientTitle: "Cliente Completo",
      clientDesc: "Contém jogo completo + sistema. Não requer patches.",
      instructions: "Instruções de Instalação:",
      step1: "1. Baixe o cliente de qualquer uma das opções.",
      step2: "2. Descompacte e execute /system/l2.exe",
      troubleshooting: "Solução de Problemas",
      troubleDesc: "Problemas para conectar? Veja este vídeo tutorial."
    },
    donate: {
      title: "Doações",
      subtitle: "Apoie o servidor e obtenha Donate Coins",
      method: "Método: Envie o comprovante ao GM pelo Discord.",
      items: "Loja de Doação: Pedras de Skin, Level Up, Karma, Troca de Nome, Cor do Nome, Armas/Armaduras AYS.",
      form: {
        charName: "Nome do Personagem",
        currency: "Moeda",
        amount: "Valor do Pagamento",
        receive: "Você Recebe (Donate Coins)",
        payMp: "Pagar com Mercado Pago",
        payPp: "Pagar com PayPal",
        payPrex: "Pagar com Prex",
        fillAll: "Por favor, preencha todos os campos"
      },
      conversion: {
        ars: "Pesos Argentinos (ARS)",
        usd: "Dólares (USD)",
        clp: "Pesos Chilenos (CLP)"
      },
      table: {
        ars: [
            { amount: "15.000", coins: "1.000" },
            { amount: "30.000", coins: "2.200" },
            { amount: "45.000", coins: "3.500" }
        ],
        usd: [
            { amount: "10", coins: "1.000" },
            { amount: "15", coins: "1.550" },
            { amount: "20", coins: "2.200" },
            { amount: "30", coins: "3.500" },
            { amount: "40", coins: "4.800" },
            { amount: "50", coins: "6.000" },
            { amount: "100", coins: "12.500" },
            { amount: "150", coins: "19.500" },
            { amount: "200", coins: "27.000" },
            { amount: "250", coins: "35.000" },
            { amount: "300.000", coins: "44.000" }
        ],
        clp: [
            { amount: "10.000", coins: "1.000" },
            { amount: "15.000", coins: "1.550" },
            { amount: "20.000", coins: "2.200" },
            { amount: "30.000", coins: "3.500" },
            { amount: "40.000", coins: "4.800" },
            { amount: "50.000", coins: "6.000" },
            { amount: "100.000", coins: "12.500" },
            { amount: "150.000", coins: "19.500" },
            { amount: "200.000", coins: "27.000" },
            { amount: "250.000", coins: "35.000" },
            { amount: "300.000", coins: "44.000" }
        ]
      }
    },
    sidebar: {
      status: "Status do Servidor",
      online: "Online",
      players: "Jogadores",
      vote: "Votar",
      login: "Painel do Usuário",
      user: "Usuário",
      pass: "Senha",
      enter: "ENTRAR",
      admin: "Login Admin",
      register: "Cadastre-se Agora!"
    },
    footer: "© 2024 [SERVER_NAME]. Servidor Lineage II Interlude."
  }
};

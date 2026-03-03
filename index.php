<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 'user_' . time() . '_' . rand(1000, 9999);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SK Education World - Math Quiz + Calculator</title>
    <style>
        /* ===== RESET & VARIABLES ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, system-ui, sans-serif;
        }

        :root {
            --primary: #4CAF50;
            --primary-dark: #2E7D32;
            --secondary: #2196F3;
            --warning: #FF9800;
            --danger: #f44336;
            --purple: #9C27B0;
            --dark: #333;
            --light: #f5f5f5;
            --white: #fff;
            --shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        body {
            background: var(--white);
            max-width: 480px;
            margin: 0 auto;
            padding: 16px;
            min-height: 100vh;
        }

        /* ===== HEADER ===== */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0 20px;
            background: var(--white);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 22px;
            box-shadow: 0 4px 12px rgba(76,175,80,0.3);
        }

        .welcome-text {
            font-size: 15px;
            color: #666;
        }

        .welcome-text span {
            color: var(--primary);
            font-size: 18px;
            font-weight: 700;
            display: block;
        }

        .welcome-text span::after {
            content: " 🌎";
        }

        .menu-icon {
            cursor: pointer;
            padding: 10px;
            background: var(--light);
            border-radius: 14px;
        }

        .menu-icon span {
            display: block;
            width: 22px;
            height: 3px;
            background: var(--dark);
            margin: 4px 0;
            border-radius: 3px;
        }

        /* ===== MENU ===== */
        .menu-dropdown {
            position: fixed;
            top: 80px;
            right: 16px;
            background: var(--white);
            border-radius: 24px;
            box-shadow: var(--shadow);
            padding: 8px;
            display: none;
            z-index: 1000;
            min-width: 220px;
            border: 1px solid #eee;
        }

        .menu-dropdown.active {
            display: block;
            animation: slideDown 0.2s;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .menu-dropdown a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            text-decoration: none;
            color: var(--dark);
            border-radius: 18px;
            transition: 0.2s;
            cursor: pointer;
        }

        .menu-dropdown a:hover {
            background: var(--light);
        }

        /* ===== SECTIONS ===== */
        .section-title {
            font-size: 24px;
            margin: 20px 0 15px;
            font-weight: 700;
            padding-left: 16px;
            border-left: 6px solid var(--primary);
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .category-card {
            background: var(--white);
            border-radius: 24px;
            padding: 24px 16px;
            text-align: center;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid #eee;
            transition: 0.3s;
        }

        .category-card:active {
            transform: scale(0.97);
            box-shadow: 0 8px 25px rgba(76,175,80,0.15);
        }

        .category-icon {
            font-size: 42px;
            margin-bottom: 10px;
            display: block;
        }

        .category-name {
            font-weight: 600;
            color: var(--dark);
        }

        /* Calculator Card Special */
        .category-card.calculator-card {
            background: linear-gradient(135deg, #FF6B6B, #FF8E53);
            color: white;
            grid-column: span 2;
        }

        .category-card.calculator-card .category-name {
            color: white;
            font-size: 18px;
        }

        /* ===== LEVEL SELECTION ===== */
        .level-selection {
            background: var(--white);
            border-radius: 32px;
            padding: 30px 24px;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .level-title {
            text-align: center;
            margin-bottom: 25px;
            font-size: 22px;
            font-weight: 700;
        }

        .level-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .level-btn {
            padding: 20px;
            border: none;
            border-radius: 20px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            color: white;
            transition: 0.2s;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .level-btn.easy { background: linear-gradient(135deg, #4CAF50, #2E7D32); }
        .level-btn.medium { background: linear-gradient(135deg, #FF9800, #F57C00); }
        .level-btn.hard { background: linear-gradient(135deg, #f44336, #C62828); }

        .level-range {
            font-size: 14px;
            background: rgba(255,255,255,0.2);
            padding: 4px 12px;
            border-radius: 40px;
        }

        .back-btn {
            margin-top: 20px;
            padding: 16px;
            border: none;
            background: var(--light);
            border-radius: 18px;
            width: 100%;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        /* ===== CALCULATOR SECTION ===== */
        .calculator-section {
            background: linear-gradient(135deg, #2C3E50, #3498DB);
            border-radius: 32px;
            padding: 24px;
            margin: 20px 0;
            animation: fadeIn 0.3s;
        }

        .calculator-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .calc-tab {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 20px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            background: rgba(255,255,255,0.2);
            color: white;
            transition: 0.3s;
        }

        .calc-tab.active {
            background: white;
            color: #2C3E50;
        }

        .calculator {
            background: white;
            border-radius: 28px;
            padding: 20px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .calc-display {
            background: #f8f9fa;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: right;
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
            min-height: 80px;
            word-wrap: break-word;
            border: 2px solid #e0e0e0;
        }

        .calc-buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .calc-btn {
            padding: 20px 10px;
            border: none;
            border-radius: 20px;
            font-size: 20px;
            font-weight: 600;
            cursor: pointer;
            background: #f8f9fa;
            color: var(--dark);
            transition: 0.2s;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        .calc-btn:active {
            transform: scale(0.95);
        }

        .calc-btn.operator {
            background: var(--warning);
            color: white;
        }

        .calc-btn.equals {
            background: var(--primary);
            color: white;
            grid-column: span 2;
        }

        .calc-btn.clear {
            background: var(--danger);
            color: white;
        }

        .calc-btn.function {
            background: var(--secondary);
            color: white;
        }

        .algebra-calc .calc-buttons {
            grid-template-columns: repeat(3, 1fr);
        }

        /* ===== GAME AREA ===== */
        .game-area {
            animation: slideUp 0.3s;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .game-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .timer {
            font-size: 32px;
            font-weight: 800;
            color: var(--danger);
            background: #FFEBEE;
            padding: 12px 24px;
            border-radius: 60px;
        }

        .timer.warning {
            animation: pulse 0.8s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .progress {
            background: #E8F5E9;
            padding: 12px 24px;
            border-radius: 60px;
            font-weight: 700;
            color: var(--primary);
        }

        .question-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 50px 20px;
            border-radius: 40px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 20px 30px rgba(102,126,234,0.3);
        }

        .question-text {
            color: white;
            font-size: 48px;
            font-weight: 800;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .options-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
            margin-bottom: 20px;
        }

        .option-btn {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 22px;
            padding: 22px 10px;
            font-size: 24px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.15s;
        }

        .option-btn.correct { background: #4CAF50; color: white; border-color: #4CAF50; }
        .option-btn.wrong { background: #f44336; color: white; border-color: #f44336; }
        .option-btn:disabled { opacity: 0.7; cursor: not-allowed; }

        .action-buttons {
            display: flex;
            gap: 14px;
        }

        .skip-btn, .submit-btn {
            flex: 1;
            padding: 18px;
            border: none;
            border-radius: 22px;
            font-size: 17px;
            font-weight: 700;
            cursor: pointer;
            color: white;
        }

        .skip-btn { background: linear-gradient(135deg, #FF9800, #F57C00); }
        .submit-btn { background: linear-gradient(135deg, #2196F3, #1976D2); }

        /* ===== MODAL ===== */
        .modal {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            padding: 20px;
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: white;
            padding: 32px 24px;
            border-radius: 40px;
            width: 100%;
            max-width: 400px;
            max-height: 80vh;
            overflow-y: auto;
            animation: modalPop 0.3s;
        }

        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .stat {
            font-size: 18px;
            margin: 12px 0;
            padding: 14px;
            border-radius: 18px;
            font-weight: 600;
        }

        .stat.correct { background: #E8F5E9; color: #2E7D32; }
        .stat.wrong { background: #FFEBEE; color: #C62828; }
        .stat.skipped { background: #FFF3E0; color: #EF6C00; }
        .stat.score { background: #E3F2FD; color: #1565C0; }

        .play-again-btn {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            color: white;
            border: none;
            padding: 18px 32px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            margin-top: 15px;
        }

        /* ===== SCOREBOARD ===== */
        .scoreboard-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            color: white;
            padding: 20px;
            border-radius: 24px;
            text-align: center;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: 800;
        }

        .history-item {
            background: #f8f9fa;
            padding: 16px;
            border-radius: 22px;
            margin-bottom: 12px;
            border: 1px solid #e0e0e0;
        }

        .history-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .history-category {
            font-weight: 700;
            color: var(--dark);
        }

        .history-difficulty {
            padding: 4px 12px;
            border-radius: 40px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .history-difficulty.easy { background: #E8F5E9; color: #2E7D32; }
        .history-difficulty.medium { background: #FFF3E0; color: #EF6C00; }
        .history-difficulty.hard { background: #FFEBEE; color: #C62828; }

        .history-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin: 12px 0;
        }

        .history-stat {
            text-align: center;
            padding: 8px;
            background: white;
            border-radius: 14px;
            font-weight: 600;
            font-size: 13px;
        }

        .history-date {
            font-size: 11px;
            color: #999;
            text-align: right;
        }

        .clear-btn {
            background: #f44336;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 50px;
            font-size: 14px;
            cursor: pointer;
            margin-top: 10px;
        }

        .hidden { display: none; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo-container">
            <div class="logo">SK</div>
            <div class="welcome-text">
                Welcome to
                <span>SK_education_world</span>
            </div>
        </div>
        <div class="menu-icon" onclick="toggleMenu()">
            <span></span><span></span><span></span>
        </div>
    </div>

    <!-- Menu -->
    <div class="menu-dropdown" id="menuDropdown">
        <a onclick="showScoreboard(); toggleMenu();">📊 Your Scoreboard</a>
        <a onclick="clearAllHistory(); toggleMenu();">🗑️ Clear History</a>
    </div>

    <!-- Categories -->
    <h2 class="section-title" id="sectionTitle">Choose Category</h2>
    <div class="categories-grid" id="categoriesGrid"></div>

    <!-- Calculator Section (Hidden by default) -->
    <div class="calculator-section hidden" id="calculatorSection">
        <div class="calculator-tabs">
            <button class="calc-tab active" onclick="switchCalculator('normal')">Normal</button>
            <button class="calc-tab" onclick="switchCalculator('algebra')">Algebra</button>
        </div>
        
        <!-- Normal Calculator -->
        <div class="calculator normal-calc" id="normalCalc">
            <div class="calc-display" id="normalDisplay">0</div>
            <div class="calc-buttons">
                <button class="calc-btn clear" onclick="clearNormalCalc()">C</button>
                <button class="calc-btn" onclick="appendNormal('(')">(</button>
                <button class="calc-btn" onclick="appendNormal(')')">)</button>
                <button class="calc-btn operator" onclick="appendNormal('÷')">÷</button>
                
                <button class="calc-btn" onclick="appendNormal('7')">7</button>
                <button class="calc-btn" onclick="appendNormal('8')">8</button>
                <button class="calc-btn" onclick="appendNormal('9')">9</button>
                <button class="calc-btn operator" onclick="appendNormal('×')">×</button>
                
                <button class="calc-btn" onclick="appendNormal('4')">4</button>
                <button class="calc-btn" onclick="appendNormal('5')">5</button>
                <button class="calc-btn" onclick="appendNormal('6')">6</button>
                <button class="calc-btn operator" onclick="appendNormal('-')">−</button>
                
                <button class="calc-btn" onclick="appendNormal('1')">1</button>
                <button class="calc-btn" onclick="appendNormal('2')">2</button>
                <button class="calc-btn" onclick="appendNormal('3')">3</button>
                <button class="calc-btn operator" onclick="appendNormal('+')">+</button>
                
                <button class="calc-btn" onclick="appendNormal('0')">0</button>
                <button class="calc-btn" onclick="appendNormal('.')">.</button>
                <button class="calc-btn" onclick="backspaceNormal()">⌫</button>
                <button class="calc-btn equals" onclick="calculateNormal()">=</button>
            </div>
        </div>

        <!-- Algebra Calculator -->
        <div class="calculator algebra-calc hidden" id="algebraCalc">
            <div class="calc-display" id="algebraDisplay">0</div>
            <div class="calc-buttons">
                <button class="calc-btn clear" onclick="clearAlgebra()">C</button>
                <button class="calc-btn" onclick="appendAlgebra('7')">7</button>
                <button class="calc-btn" onclick="appendAlgebra('8')">8</button>
                <button class="calc-btn" onclick="appendAlgebra('9')">9</button>
                
                <button class="calc-btn function" onclick="calculateSquare()">x²</button>
                <button class="calc-btn" onclick="appendAlgebra('4')">4</button>
                <button class="calc-btn" onclick="appendAlgebra('5')">5</button>
                <button class="calc-btn" onclick="appendAlgebra('6')">6</button>
                
                <button class="calc-btn function" onclick="calculateCube()">x³</button>
                <button class="calc-btn" onclick="appendAlgebra('1')">1</button>
                <button class="calc-btn" onclick="appendAlgebra('2')">2</button>
                <button class="calc-btn" onclick="appendAlgebra('3')">3</button>
                
                <button class="calc-btn function" onclick="calculateSquareRoot()">√x</button>
                <button class="calc-btn" onclick="appendAlgebra('0')">0</button>
                <button class="calc-btn" onclick="appendAlgebra('.')">.</button>
                <button class="calc-btn function" onclick="calculateCubeRoot()">∛x</button>
                
                <button class="calc-btn" onclick="backspaceAlgebra()">⌫</button>
                <button class="calc-btn equals" colspan="2" onclick="calculateAlgebra()">=</button>
            </div>
        </div>

        <button class="back-btn" onclick="backToMainFromCalc()" style="margin-top: 20px;">← Back to Main</button>
    </div>

    <!-- Level Selection (For Quiz) -->
    <div class="level-selection hidden" id="levelSelection">
        <h3 class="level-title" id="selectedCategoryTitle">Select Level</h3>
        <div class="level-buttons">
            <button class="level-btn easy" onclick="startGame('easy')">
                Easy <span class="level-range">1-20</span>
            </button>
            <button class="level-btn medium" onclick="startGame('medium')">
                Medium <span class="level-range">21-50</span>
            </button>
            <button class="level-btn hard" onclick="startGame('hard')">
                Hard <span class="level-range">51-100</span>
            </button>
        </div>
        <button class="back-btn" onclick="backToMain()">← Back</button>
    </div>

    <!-- Game Area -->
    <div class="game-area hidden" id="gameArea">
        <div class="game-header">
            <div class="timer" id="timer">20s</div>
            <div class="progress" id="progress">Q<span id="currentQ">1</span>/<span id="totalQ">10</span></div>
        </div>
        <div class="question-card">
            <div class="question-text" id="questionText">Loading...</div>
        </div>
        <div class="options-grid" id="optionsGrid"></div>
        <div class="action-buttons">
            <button class="skip-btn" onclick="skipQuestion()">⏭️ Skip</button>
            <button class="submit-btn" onclick="endQuiz()">📥 Submit</button>
        </div>
    </div>

    <!-- Result Modal -->
    <div class="modal hidden" id="resultModal">
        <div class="modal-content">
            <h2>🎉 Quiz Complete!</h2>
            <div class="stat correct">✅ Correct: <span id="correctCount">0</span></div>
            <div class="stat wrong">❌ Wrong: <span id="wrongCount">0</span></div>
            <div class="stat skipped">⏭️ Skipped: <span id="skippedCount">0</span></div>
            <div class="stat score">⭐ Score: <span id="totalScore">0</span>/100</div>
            <button class="play-again-btn" onclick="playAgain()">Play Again</button>
        </div>
    </div>

    <!-- Scoreboard Modal -->
    <div class="modal hidden" id="scoreboardModal">
        <div class="modal-content">
            <h2>📊 Your Scoreboard</h2>
            <div id="scoreboardContent"></div>
            <button class="play-again-btn" onclick="closeScoreboard()">Close</button>
            <button class="clear-btn" onclick="clearHistory()">Clear All History</button>
        </div>
    </div>

    <script>
        // ==================== GAME CONFIGURATION ====================
        const categories = [
            { id: 1, name: 'Square Root', icon: '√', color: '#4CAF50' },
            { id: 2, name: 'Cube Root', icon: '∛', color: '#2196F3' },
            { id: 3, name: 'Square Numbers', icon: 'x²', color: '#FF9800' },
            { id: 4, name: 'Cube Numbers', icon: 'x³', color: '#9C27B0' },
            { id: 5, name: 'Calculator', icon: '🧮', color: '#FF6B6B', isCalculator: true }
        ];

        // Game variables
        let currentCategory = '';
        let currentDifficulty = '';
        let questions = [];
        let currentIndex = 0;
        let score = 0;
        let correct = 0;
        let wrong = 0;
        let skipped = 0;
        let timer;
        let timeLeft = 20;
        const totalQuestions = 10;

        // Calculator variables
        let normalExpression = '';
        let algebraValue = '';

        // ==================== STORAGE FUNCTIONS ====================
        function saveGameToStorage() {
            const gameData = {
                id: Date.now(),
                category: currentCategory,
                difficulty: currentDifficulty,
                correct: correct,
                wrong: wrong,
                skipped: skipped,
                score: score,
                total: totalQuestions,
                date: new Date().toLocaleString()
            };
            
            let history = JSON.parse(localStorage.getItem('quizHistory')) || [];
            history.unshift(gameData);
            if (history.length > 50) history.pop();
            localStorage.setItem('quizHistory', JSON.stringify(history));
        }

        function getGameHistory() {
            return JSON.parse(localStorage.getItem('quizHistory')) || [];
        }

        // ==================== CATEGORIES ====================
        function loadCategories() {
            const grid = document.getElementById('categoriesGrid');
            grid.innerHTML = '';
            
            categories.forEach(cat => {
                if (cat.isCalculator) {
                    grid.innerHTML += `
                        <div class="category-card calculator-card" onclick="openCalculator()">
                            <span class="category-icon">${cat.icon}</span>
                            <div class="category-name">${cat.name}</div>
                        </div>
                    `;
                } else {
                    grid.innerHTML += `
                        <div class="category-card" onclick="selectCategory('${cat.name}')">
                            <span class="category-icon" style="color: ${cat.color}">${cat.icon}</span>
                            <div class="category-name">${cat.name}</div>
                        </div>
                    `;
                }
            });
        }

        function selectCategory(category) {
            currentCategory = category;
            document.getElementById('selectedCategoryTitle').innerHTML = `${category} - Select Level`;
            document.getElementById('sectionTitle').classList.add('hidden');
            document.getElementById('categoriesGrid').classList.add('hidden');
            document.getElementById('levelSelection').classList.remove('hidden');
        }

        function backToMain() {
            document.getElementById('sectionTitle').classList.remove('hidden');
            document.getElementById('categoriesGrid').classList.remove('hidden');
            document.getElementById('levelSelection').classList.add('hidden');
            document.getElementById('gameArea').classList.add('hidden');
            clearInterval(timer);
        }

        function backToMainFromCalc() {
            document.getElementById('sectionTitle').classList.remove('hidden');
            document.getElementById('categoriesGrid').classList.remove('hidden');
            document.getElementById('calculatorSection').classList.add('hidden');
        }

        // ==================== CALCULATOR FUNCTIONS ====================
        function openCalculator() {
            document.getElementById('sectionTitle').classList.add('hidden');
            document.getElementById('categoriesGrid').classList.add('hidden');
            document.getElementById('calculatorSection').classList.remove('hidden');
            resetCalculators();
        }

        function switchCalculator(type) {
            const normalCalc = document.getElementById('normalCalc');
            const algebraCalc = document.getElementById('algebraCalc');
            const tabs = document.querySelectorAll('.calc-tab');
            
            tabs.forEach(tab => tab.classList.remove('active'));
            
            if (type === 'normal') {
                normalCalc.classList.remove('hidden');
                algebraCalc.classList.add('hidden');
                tabs[0].classList.add('active');
            } else {
                normalCalc.classList.add('hidden');
                algebraCalc.classList.remove('hidden');
                tabs[1].classList.add('active');
            }
        }

        function resetCalculators() {
            normalExpression = '';
            algebraValue = '';
            document.getElementById('normalDisplay').innerHTML = '0';
            document.getElementById('algebraDisplay').innerHTML = '0';
        }

        // Normal Calculator Functions
        function appendNormal(value) {
            normalExpression += value;
            document.getElementById('normalDisplay').innerHTML = normalExpression;
        }

        function clearNormalCalc() {
            normalExpression = '';
            document.getElementById('normalDisplay').innerHTML = '0';
        }

        function backspaceNormal() {
            normalExpression = normalExpression.slice(0, -1);
            document.getElementById('normalDisplay').innerHTML = normalExpression || '0';
        }

        function calculateNormal() {
            try {
                let expression = normalExpression.replace(/×/g, '*').replace(/÷/g, '/');
                let result = eval(expression);
                document.getElementById('normalDisplay').innerHTML = result;
                normalExpression = result.toString();
            } catch(e) {
                document.getElementById('normalDisplay').innerHTML = 'Error';
                normalExpression = '';
            }
        }

        // Algebra Calculator Functions
        function appendAlgebra(value) {
            algebraValue += value;
            document.getElementById('algebraDisplay').innerHTML = algebraValue;
        }

        function clearAlgebra() {
            algebraValue = '';
            document.getElementById('algebraDisplay').innerHTML = '0';
        }

        function backspaceAlgebra() {
            algebraValue = algebraValue.slice(0, -1);
            document.getElementById('algebraDisplay').innerHTML = algebraValue || '0';
        }

        function calculateSquare() {
            let num = parseFloat(algebraValue) || 0;
            let result = num * num;
            document.getElementById('algebraDisplay').innerHTML = result;
            algebraValue = result.toString();
        }

        function calculateCube() {
            let num = parseFloat(algebraValue) || 0;
            let result = num * num * num;
            document.getElementById('algebraDisplay').innerHTML = result;
            algebraValue = result.toString();
        }

        function calculateSquareRoot() {
            let num = parseFloat(algebraValue) || 0;
            if (num < 0) {
                document.getElementById('algebraDisplay').innerHTML = 'Error';
                algebraValue = '';
            } else {
                let result = Math.sqrt(num);
                document.getElementById('algebraDisplay').innerHTML = result;
                algebraValue = result.toString();
            }
        }

        function calculateCubeRoot() {
            let num = parseFloat(algebraValue) || 0;
            let result = Math.cbrt(num);
            document.getElementById('algebraDisplay').innerHTML = result;
            algebraValue = result.toString();
        }

        function calculateAlgebra() {
            try {
                let result = eval(algebraValue);
                document.getElementById('algebraDisplay').innerHTML = result;
                algebraValue = result.toString();
            } catch(e) {
                document.getElementById('algebraDisplay').innerHTML = algebraValue || '0';
            }
        }

        // ==================== GAME FUNCTIONS ====================
        function startGame(difficulty) {
            currentDifficulty = difficulty;
            document.getElementById('levelSelection').classList.add('hidden');
            document.getElementById('gameArea').classList.remove('hidden');
            generateQuestions();
        }

        function generateQuestions() {
            questions = [];
            
            for(let i = 0; i < totalQuestions; i++) {
                let q = {};
                let num, range;
                
                switch(currentDifficulty) {
                    case 'easy': range = { min: 1, max: 20 }; break;
                    case 'medium': range = { min: 21, max: 50 }; break;
                    case 'hard': range = { min: 51, max: 100 }; break;
                }
                
                switch(currentCategory) {
                    case 'Square Root':
                        num = Math.floor(Math.random() * (range.max - range.min + 1)) + range.min;
                        q.text = `√${num * num} = ?`;
                        q.answer = num;
                        break;
                        
                    case 'Cube Root':
                        num = Math.floor(Math.random() * (30 - range.min + 1)) + range.min;
                        q.text = `∛${num * num * num} = ?`;
                        q.answer = num;
                        break;
                        
                    case 'Square Numbers':
                        num = Math.floor(Math.random() * (range.max - range.min + 1)) + range.min;
                        q.text = `${num}² = ?`;
                        q.answer = num * num;
                        break;
                        
                    case 'Cube Numbers':
                        num = Math.floor(Math.random() * (30 - range.min + 1)) + range.min;
                        q.text = `${num}³ = ?`;
                        q.answer = num * num * num;
                        break;
                }
                
                let options = [q.answer];
                while(options.length < 4) {
                    let offset = Math.floor(Math.random() * 20) - 10;
                    if(offset === 0) offset = 1;
                    let opt = q.answer + offset;
                    if(opt > 0 && !options.includes(opt)) {
                        options.push(opt);
                    }
                }
                
                q.options = shuffleArray(options);
                questions.push(q);
            }
            
            currentIndex = 0;
            score = correct = wrong = skipped = 0;
            displayQuestion();
        }

        function shuffleArray(array) {
            for(let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
            return array;
        }

        function displayQuestion() {
            if(currentIndex >= questions.length) {
                endQuiz();
                return;
            }
            
            const q = questions[currentIndex];
            document.getElementById('questionText').innerHTML = q.text;
            document.getElementById('currentQ').innerHTML = currentIndex + 1;
            document.getElementById('totalQ').innerHTML = totalQuestions;
            
            let optionsHtml = '';
            q.options.forEach(opt => {
                optionsHtml += `<button class="option-btn" onclick="checkAnswer(${opt}, this)">${opt}</button>`;
            });
            document.getElementById('optionsGrid').innerHTML = optionsHtml;
            
            startTimer();
        }

        function startTimer() {
            clearInterval(timer);
            timeLeft = 20;
            document.getElementById('timer').innerHTML = timeLeft + 's';
            document.getElementById('timer').classList.remove('warning');
            
            timer = setInterval(() => {
                timeLeft--;
                document.getElementById('timer').innerHTML = timeLeft + 's';
                
                if(timeLeft <= 5) {
                    document.getElementById('timer').classList.add('warning');
                }
                
                if(timeLeft <= 0) {
                    clearInterval(timer);
                    timeOut();
                }
            }, 1000);
        }

        function checkAnswer(selected, btn) {
            clearInterval(timer);
            const q = questions[currentIndex];
            const options = document.querySelectorAll('.option-btn');
            
            options.forEach(opt => opt.disabled = true);
            
            if(selected == q.answer) {
                btn.classList.add('correct');
                correct++;
                score += 10;
            } else {
                btn.classList.add('wrong');
                wrong++;
                options.forEach(opt => {
                    if(parseFloat(opt.innerHTML) == q.answer) {
                        opt.classList.add('correct');
                    }
                });
            }
            
            setTimeout(() => {
                currentIndex++;
                displayQuestion();
            }, 1000);
        }

        function timeOut() {
            wrong++;
            const q = questions[currentIndex];
            const options = document.querySelectorAll('.option-btn');
            
            options.forEach(opt => opt.disabled = true);
            options.forEach(opt => {
                if(parseFloat(opt.innerHTML) == q.answer) {
                    opt.classList.add('correct');
                }
            });
            
            setTimeout(() => {
                currentIndex++;
                displayQuestion();
            }, 1000);
        }

        function skipQuestion() {
            clearInterval(timer);
            skipped++;
            
            const q = questions[currentIndex];
            const options = document.querySelectorAll('.option-btn');
            
            options.forEach(opt => opt.disabled = true);
            options.forEach(opt => {
                if(parseFloat(opt.innerHTML) == q.answer) {
                    opt.classList.add('correct');
                }
            });
            
            setTimeout(() => {
                currentIndex++;
                displayQuestion();
            }, 800);
        }

        function endQuiz() {
            clearInterval(timer);
            document.getElementById('gameArea').classList.add('hidden');
            
            document.getElementById('correctCount').innerHTML = correct;
            document.getElementById('wrongCount').innerHTML = wrong;
            document.getElementById('skippedCount').innerHTML = skipped;
            document.getElementById('totalScore').innerHTML = score;
            
            document.getElementById('resultModal').classList.remove('hidden');
            saveGameToStorage();
        }

        function playAgain() {
            document.getElementById('resultModal').classList.add('hidden');
            document.getElementById('sectionTitle').classList.remove('hidden');
            document.getElementById('categoriesGrid').classList.remove('hidden');
        }

        // ==================== SCOREBOARD ====================
        function showScoreboard() {
            const history = getGameHistory();
            const content = document.getElementById('scoreboardContent');
            
            if (history.length === 0) {
                content.innerHTML = '<div style="text-align:center; padding:30px;">No games yet! Play some quizzes 🎮</div>';
            } else {
                let totalScore = 0, totalCorrect = 0, totalGames = history.length;
                history.forEach(game => {
                    totalScore += game.score || 0;
                    totalCorrect += game.correct || 0;
                });
                
                let html = `
                    <div class="scoreboard-stats">
                        <div class="stat-card">
                            <div class="number">${totalGames}</div>
                            <div>Total Games</div>
                        </div>
                        <div class="stat-card">
                            <div class="number">${totalScore}</div>
                            <div>Total Score</div>
                        </div>
                    </div>
                    <h3 style="margin:15px 0;">Recent Games</h3>
                `;
                
                history.slice(0, 10).forEach(game => {
                    html += `
                        <div class="history-item">
                            <div class="history-header">
                                <span class="history-category">${game.category}</span>
                                <span class="history-difficulty ${game.difficulty}">${game.difficulty}</span>
                            </div>
                            <div class="history-stats">
                                <div class="history-stat" style="color:#4CAF50;">✅ ${game.correct}</div>
                                <div class="history-stat" style="color:#f44336;">❌ ${game.wrong}</div>
                                <div class="history-stat" style="color:#FF9800;">⏭️ ${game.skipped}</div>
                                <div class="history-stat" style="color:#2196F3;">⭐ ${game.score}</div>
                            </div>
                            <div class="history-date">${game.date}</div>
                        </div>
                    `;
                });
                
                content.innerHTML = html;
            }
            
            document.getElementById('scoreboardModal').classList.remove('hidden');
        }

        function closeScoreboard() {
            document.getElementById('scoreboardModal').classList.add('hidden');
        }

        function clearHistory() {
            if (confirm('Delete all game history?')) {
                localStorage.removeItem('quizHistory');
                showScoreboard();
            }
        }

        function clearAllHistory() {
            clearHistory();
        }

        // ==================== MENU ====================
        function toggleMenu() {
            document.getElementById('menuDropdown').classList.toggle('active');
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('menuDropdown');
            const icon = document.querySelector('.menu-icon');
            
            if (menu && !menu.contains(event.target) && !icon.contains(event.target)) {
                menu.classList.remove('active');
            }
        });

        // Initialize
        window.onload = loadCategories;
    </script>
</body>
</html>
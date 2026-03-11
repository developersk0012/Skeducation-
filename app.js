// ==================== APP MODULE ====================
const app = {
    // State
    state: {
        currentCategory: '',
        currentDifficulty: '',
        score: 0,
        correct: 0,
        wrong: 0,
        skipped: 0,
        questionCount: 0,
        timer: null,
        timeLeft: 20,
        currentQuestion: null
    },

    // Constants
    categories: [
        { id: 1, name: 'Square Root', icon: '√', color: '#4CAF50' },
        { id: 2, name: 'Cube Root', icon: '∛', color: '#2196F3' },
        { id: 3, name: 'Square Numbers', icon: 'x²', color: '#FF9800' },
        { id: 4, name: 'Cube Numbers', icon: 'x³', color: '#9C27B0' },
        { id: 5, name: 'Calculator', icon: '🧮', isCalculator: true }
    ],

    calculator: {
        normalExpression: '',
        algebraValue: '',
        normalButtons: [
            ['C', '(', ')', '÷'],
            ['7', '8', '9', '×'],
            ['4', '5', '6', '-'],
            ['1', '2', '3', '+'],
            ['0', '.', '⌫', '=']
        ],
        algebraButtons: [
            ['C', '7', '8', '9', 'x²'],
            ['x³', '4', '5', '6', '√x'],
            ['∛x', '1', '2', '3', '+'],
            ['-', '0', '.', '⌫', '×'],
            ['÷', '=', '', '', '']
        ]
    },

    // ==================== INITIALIZATION ====================
    init() {
        this.renderCategories();
        this.attachEventListeners();
        this.loadFromStorage();
    },

    attachEventListeners() {
        // Menu
        document.getElementById('menuIcon').addEventListener('click', (e) => {
            e.stopPropagation();
            this.methods.toggleMenu();
        });

        // Close menu on outside click
        document.addEventListener('click', () => {
            document.getElementById('menuDropdown').classList.remove('active');
        });

        // Prevent menu close when clicking inside menu
        document.getElementById('menuDropdown').addEventListener('click', (e) => {
            e.stopPropagation();
        });
    },

    // ==================== RENDERING ====================
    renderCategories() {
        const appDiv = document.getElementById('app');
        const template = document.getElementById('categories-template').content.cloneNode(true);
        const grid = template.querySelector('#categoriesGrid');
        
        this.categories.forEach(cat => {
            const card = document.createElement('div');
            card.className = cat.isCalculator ? 'category-card calculator-card' : 'category-card';
            if (cat.isCalculator) {
                card.onclick = () => this.methods.openCalculator();
            } else {
                card.onclick = () => this.methods.selectCategory(cat.name);
            }
            
            card.innerHTML = `
                <span class="category-icon" style="color: ${cat.color || 'white'}">${cat.icon}</span>
                <div class="category-name">${cat.name}</div>
            `;
            grid.appendChild(card);
        });
        
        appDiv.innerHTML = '';
        appDiv.appendChild(template);
    },

    renderLevelSelection(category) {
        const appDiv = document.getElementById('app');
        const template = document.getElementById('level-template').content.cloneNode(true);
        
        template.querySelector('#selectedCategoryTitle').textContent = `${category} - Select Level`;
        template.querySelectorAll('.level-btn').forEach(btn => {
            btn.onclick = () => this.methods.startGame(btn.dataset.level);
        });
        template.querySelector('#backToMainBtn').onclick = () => this.methods.backToMain();
        
        appDiv.innerHTML = '';
        appDiv.appendChild(template);
    },

    renderGame() {
        const appDiv = document.getElementById('app');
        const template = document.getElementById('game-template').content.cloneNode(true);
        
        template.querySelector('#skipBtn').onclick = () => this.methods.skipQuestion();
        template.querySelector('#submitBtn').onclick = () => this.methods.endQuiz();
        
        appDiv.innerHTML = '';
        appDiv.appendChild(template);
        
        this.methods.generateNewQuestion();
    },

    renderCalculator() {
        const appDiv = document.getElementById('app');
        const template = document.getElementById('calculator-template').content.cloneNode(true);
        
        // Render normal calculator buttons
        const normalButtons = template.querySelector('#normalButtons');
        this.calculator.normalButtons.forEach(row => {
            row.forEach(btn => {
                const button = document.createElement('button');
                button.className = this.getButtonClass(btn);
                button.textContent = btn;
                button.onclick = () => this.methods.handleNormalCalc(btn);
                normalButtons.appendChild(button);
            });
        });

        // Render algebra calculator buttons
        const algebraButtons = template.querySelector('#algebraButtons');
        this.calculator.algebraButtons.flat().forEach(btn => {
            if (btn) {
                const button = document.createElement('button');
                button.className = this.getButtonClass(btn);
                button.textContent = btn;
                button.onclick = () => this.methods.handleAlgebraCalc(btn);
                algebraButtons.appendChild(button);
            }
        });

        template.querySelectorAll('.calc-tab').forEach(tab => {
            tab.onclick = () => this.methods.switchCalculator(tab.dataset.calc);
        });
        template.querySelector('#backFromCalcBtn').onclick = () => this.methods.backToMain();
        
        appDiv.innerHTML = '';
        appDiv.appendChild(template);
        
        this.methods.resetCalculators();
    },

    renderResult() {
        const modal = document.getElementById('result-template').content.cloneNode(true);
        
        modal.querySelector('#correctCount').textContent = this.state.correct;
        modal.querySelector('#wrongCount').textContent = this.state.wrong;
        modal.querySelector('#skippedCount').textContent = this.state.skipped;
        modal.querySelector('#totalScore').textContent = this.state.score;
        modal.querySelector('#playAgainBtn').onclick = () => this.methods.playAgain();
        
        document.body.appendChild(modal);
    },

    renderScoreboard() {
        const history = this.methods.getGameHistory();
        const modal = document.getElementById('scoreboard-template').content.cloneNode(true);
        const content = modal.querySelector('#scoreboardContent');
        
        if (history.length === 0) {
            content.innerHTML = '<div style="text-align:center; padding:30px;">No games yet! Play some quizzes 🎮</div>';
        } else {
            let totalScore = 0, totalGames = history.length;
            history.forEach(game => totalScore += game.score || 0);
            
            let html = `
                <div class="scoreboard-stats">
                    <div class="stat-card"><div class="number">${totalGames}</div><div>Total Games</div></div>
                    <div class="stat-card"><div class="number">${totalScore}</div><div>Total Score</div></div>
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
        
        modal.querySelector('#closeScoreboardBtn').onclick = () => modal.remove();
        modal.querySelector('#clearHistoryBtn').onclick = () => {
            if (confirm('Delete all game history?')) {
                localStorage.removeItem('quizHistory');
                modal.remove();
                this.methods.showScoreboard();
            }
        };
        
        document.body.appendChild(modal);
    },

    getButtonClass(btn) {
        if (btn === 'C') return 'calc-btn clear';
        if (btn === '=') return 'calc-btn equals';
        if (['+', '-', '×', '÷'].includes(btn)) return 'calc-btn operator';
        if (['x²', 'x³', '√x', '∛x', '⌫'].includes(btn)) return 'calc-btn function';
        return 'calc-btn';
    },

    // ==================== GAME METHODS ====================
    methods: {
        toggleMenu() {
            document.getElementById('menuDropdown').classList.toggle('active');
        },

        selectCategory(category) {
            app.state.currentCategory = category;
            app.renderLevelSelection(category);
        },

        backToMain() {
            if (app.state.timer) clearInterval(app.state.timer);
            app.renderCategories();
        },

        startGame(difficulty) {
            app.state.currentDifficulty = difficulty;
            app.state.score = 0;
            app.state.correct = 0;
            app.state.wrong = 0;
            app.state.skipped = 0;
            app.state.questionCount = 0;
            
            app.renderGame();
        },

        generateNewQuestion() {
            app.state.questionCount++;
            document.getElementById('currentQ').textContent = app.state.questionCount;
            
            const range = {
                easy: { min: 1, max: 20 },
                medium: { min: 21, max: 50 },
                hard: { min: 51, max: 100 }
            }[app.state.currentDifficulty];
            
            let num = Math.floor(Math.random() * (range.max - range.min + 1)) + range.min;
            let answer, questionText;
            
            switch(app.state.currentCategory) {
                case 'Square Root':
                    answer = num;
                    questionText = `√${num * num} = ?`;
                    break;
                case 'Cube Root':
                    let cubeNum = Math.floor(Math.random() * 10) + 1;
                    answer = cubeNum;
                    questionText = `∛${cubeNum * cubeNum * cubeNum} = ?`;
                    break;
                case 'Square Numbers':
                    answer = num * num;
                    questionText = `${num}² = ?`;
                    break;
                case 'Cube Numbers':
                    let smallNum = Math.floor(Math.random() * 10) + 1;
                    answer = smallNum * smallNum * smallNum;
                    questionText = `${smallNum}³ = ?`;
                    break;
                default:
                    answer = num;
                    questionText = `${num} = ?`;
            }
            
            let options = [answer];
            while(options.length < 4) {
                let offset = Math.floor(Math.random() * 10) - 5;
                if(offset === 0) offset = 1;
                let opt = answer + offset;
                if(opt > 0 && !options.includes(opt)) options.push(opt);
            }
            
            app.state.currentQuestion = {
                text: questionText,
                answer: answer,
                options: this.shuffleArray(options)
            };
            
            this.displayQuestion();
        },

        shuffleArray(array) {
            for(let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
            return array;
        },

        displayQuestion() {
            const q = app.state.currentQuestion;
            document.getElementById('questionText').textContent = q.text;
            
            let optionsHtml = '';
            q.options.forEach(opt => {
                optionsHtml += `<button class="option-btn" onclick="app.methods.checkAnswer(${opt})">${opt}</button>`;
            });
            document.getElementById('optionsGrid').innerHTML = optionsHtml;
            
            this.startTimer();
        },

        startTimer() {
            if (app.state.timer) clearInterval(app.state.timer);
            
            app.state.timeLeft = 20;
            document.getElementById('timer').textContent = app.state.timeLeft + 's';
            document.getElementById('timer').classList.remove('warning');
            
            app.state.timer = setInterval(() => {
                app.state.timeLeft--;
                document.getElementById('timer').textContent = app.state.timeLeft + 's';
                
                if(app.state.timeLeft <= 5) {
                    document.getElementById('timer').classList.add('warning');
                }
                
                if(app.state.timeLeft <= 0) {
                    clearInterval(app.state.timer);
                    this.timeOut();
                }
            }, 1000);
        },

        checkAnswer(selected) {
            clearInterval(app.state.timer);
            
            const q = app.state.currentQuestion;
            const options = document.querySelectorAll('.option-btn');
            
            options.forEach(opt => opt.disabled = true);
            
            if(selected == q.answer) {
                event.target.classList.add('correct');
                app.state.correct++;
                app.state.score += 10;
            } else {
                event.target.classList.add('wrong');
                app.state.wrong++;
                options.forEach(opt => {
                    if(parseFloat(opt.textContent) == q.answer) {
                        opt.classList.add('correct');
                    }
                });
            }
            
            setTimeout(() => this.generateNewQuestion(), 800);
        },

        timeOut() {
            app.state.wrong++;
            const q = app.state.currentQuestion;
            const options = document.querySelectorAll('.option-btn');
            
            options.forEach(opt => opt.disabled = true);
            options.forEach(opt => {
                if(parseFloat(opt.textContent) == q.answer) {
                    opt.classList.add('correct');
                }
            });
            
            setTimeout(() => this.generateNewQuestion(), 800);
        },

        skipQuestion() {
            clearInterval(app.state.timer);
            app.state.skipped++;
            
            const q = app.state.currentQuestion;
            const options = document.querySelectorAll('.option-btn');
            
            options.forEach(opt => opt.disabled = true);
            options.forEach(opt => {
                if(parseFloat(opt.textContent) == q.answer) {
                    opt.classList.add('correct');
                }
            });
            
            setTimeout(() => this.generateNewQuestion(), 600);
        },

        endQuiz() {
            clearInterval(app.state.timer);
            app.renderResult();
            this.saveGameToStorage();
        },

        playAgain() {
            document.querySelector('.modal').remove();
            app.methods.backToMain();
        },

        // ==================== CALCULATOR METHODS ====================
        openCalculator() {
            app.renderCalculator();
        },

        switchCalculator(type) {
            document.getElementById('normalCalc').classList.toggle('hidden', type !== 'normal');
            document.getElementById('algebraCalc').classList.toggle('hidden', type !== 'algebra');
            document.querySelectorAll('.calc-tab').forEach(tab => {
                tab.classList.toggle('active', tab.dataset.calc === type);
            });
        },

        resetCalculators() {
            app.calculator.normalExpression = '';
            app.calculator.algebraValue = '';
            document.getElementById('normalDisplay').textContent = '0';
            document.getElementById('algebraDisplay').textContent = '0';
        },

        handleNormalCalc(btn) {
            if (btn === 'C') {
                app.calculator.normalExpression = '';
                document.getElementById('normalDisplay').textContent = '0';
            } else if (btn === '⌫') {
                app.calculator.normalExpression = app.calculator.normalExpression.slice(0, -1);
                document.getElementById('normalDisplay').textContent = app.calculator.normalExpression || '0';
            } else if (btn === '=') {
                try {
                    let expr = app.calculator.normalExpression.replace(/×/g, '*').replace(/÷/g, '/');
                    let result = eval(expr);
                    document.getElementById('normalDisplay').textContent = result;
                    app.calculator.normalExpression = result.toString();
                } catch {
                    document.getElementById('normalDisplay').textContent = 'Error';
                    app.calculator.normalExpression = '';
                }
            } else {
                app.calculator.normalExpression += btn;
                document.getElementById('normalDisplay').textContent = app.calculator.normalExpression;
            }
        },

        handleAlgebraCalc(btn) {
            if (btn === 'C') {
                app.calculator.algebraValue = '';
                document.getElementById('algebraDisplay').textContent = '0';
            } else if (btn === '⌫') {
                app.calculator.algebraValue = app.calculator.algebraValue.slice(0, -1);
                document.getElementById('algebraDisplay').textContent = app.calculator.algebraValue || '0';
            } else if (btn === 'x²') {
                let num = parseFloat(app.calculator.algebraValue) || 0;
                let result = num * num;
                document.getElementById('algebraDisplay').textContent = result;
                app.calculator.algebraValue = result.toString();
            } else if (btn === 'x³') {
                let num = parseFloat(app.calculator.algebraValue) || 0;
                let result = num * num * num;
                document.getElementById('algebraDisplay').textContent = result;
                app.calculator.algebraValue = result.toString();
            } else if (btn === '√x') {
                let num = parseFloat(app.calculator.algebraValue) || 0;
                if (num < 0) {
                    document.getElementById('algebraDisplay').textContent = 'Error';
                    app.calculator.algebraValue = '';
                } else {
                    let result = Math.sqrt(num);
                    document.getElementById('algebraDisplay').textContent = result;
                    app.calculator.algebraValue = result.toString();
                }
            } else if (btn === '∛x') {
                let num = parseFloat(app.calculator.algebraValue) || 0;
                let result = Math.cbrt(num);
                document.getElementById('algebraDisplay').textContent = result;
                app.calculator.algebraValue = result.toString();
            } else if (btn === '=') {
                try {
                    let expr = app.calculator.algebraValue.replace(/×/g, '*').replace(/÷/g, '/');
                    let result = eval(expr);
                    document.getElementById('algebraDisplay').textContent = result;
                    app.calculator.algebraValue = result.toString();
                } catch {
                    document.getElementById('algebraDisplay').textContent = app.calculator.algebraValue || '0';
                }
            } else {
                app.calculator.algebraValue += btn;
                document.getElementById('algebraDisplay').textContent = app.calculator.algebraValue;
            }
        },

        // ==================== STORAGE METHODS ====================
        saveGameToStorage() {
            const gameData = {
                id: Date.now(),
                category: app.state.currentCategory,
                difficulty: app.state.currentDifficulty,
                correct: app.state.correct,
                wrong: app.state.wrong,
                skipped: app.state.skipped,
                score: app.state.score,
                total: app.state.questionCount,
                date: new Date().toLocaleString()
            };
            
            let history = this.getGameHistory();
            history.unshift(gameData);
            if (history.length > 50) history.pop();
            localStorage.setItem('quizHistory', JSON.stringify(history));
        },

        getGameHistory() {
            return JSON.parse(localStorage.getItem('quizHistory')) || [];
        },

        showScoreboard() {
            app.renderScoreboard();
        },

        clearAllHistory() {
            if (confirm('Delete all game history?')) {
                localStorage.removeItem('quizHistory');
            }
        }
    },

    loadFromStorage() {
        // Pre-load any saved data if needed
    }
};

// Initialize app when DOM is ready
document.addEventListener('DOMContentLoaded', () => app.init());
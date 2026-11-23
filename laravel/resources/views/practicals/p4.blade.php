@extends('layouts.app')

@section('title', 'Practical 4 — Interactive Keyboard Calculator | GMCA Laravel')

@section('content')
<div style="max-width: 650px; margin: 10px auto; display: flex; flex-direction: column; gap: 20px;">

    <div style="text-align: center;">
        <span class="badge badge-navy" style="margin-bottom: 6px;">Practical 4 • Lead: Vaibhav Senjaliya (26GMCA52)</span>
        <h1 style="font-size: 26px; font-weight: 800; color: var(--navy); margin: 4px 0 6px;">
            Interactive Keypad Calculator
        </h1>
        <p style="color: var(--text-muted); font-size: 13.5px;">
            Supports tactile on-screen clicks and direct physical keyboard keystrokes.
        </p>
    </div>

    <!-- Calculator Card -->
    <div class="card" style="border: 2px solid var(--navy); background: #0F172A; color: #FFFFFF; padding: 24px; border-radius: 16px; box-shadow: 0 15px 30px rgba(0, 0, 128, 0.2);">
        
        <!-- Screen Display -->
        <div style="background: #1E293B; border: 1px solid #334155; border-radius: 10px; padding: 16px 20px; margin-bottom: 20px; text-align: right;">
            <div id="calcHistory" style="font-size: 13px; color: #94A3B8; min-height: 20px; font-family: 'Fira Code', monospace; overflow-x: auto; white-space: nowrap;"></div>
            <div id="calcDisplay" style="font-size: 32px; font-weight: 800; color: #38BDF8; font-family: 'Fira Code', monospace; overflow-x: auto; white-space: nowrap;">0</div>
        </div>

        <!-- 4-Column Calculator Keypad -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;">
            <!-- Row 1 -->
            <button type="button" class="calc-btn" onclick="calcClear()" style="background: #DC2626; color: #FFFFFF;">C</button>
            <button type="button" class="calc-btn" onclick="calcAppend('(')" style="background: #334155; color: #E2E8F0;">(</button>
            <button type="button" class="calc-btn" onclick="calcAppend(')')" style="background: #334155; color: #E2E8F0;">)</button>
            <button type="button" class="calc-btn" onclick="calcAppend('/')" style="background: var(--saffron); color: #FFFFFF;">÷</button>

            <!-- Row 2 -->
            <button type="button" class="calc-btn" onclick="calcAppend('7')">7</button>
            <button type="button" class="calc-btn" onclick="calcAppend('8')">8</button>
            <button type="button" class="calc-btn" onclick="calcAppend('9')">9</button>
            <button type="button" class="calc-btn" onclick="calcAppend('*')" style="background: var(--saffron); color: #FFFFFF;">×</button>

            <!-- Row 3 -->
            <button type="button" class="calc-btn" onclick="calcAppend('4')">4</button>
            <button type="button" class="calc-btn" onclick="calcAppend('5')">5</button>
            <button type="button" class="calc-btn" onclick="calcAppend('6')">6</button>
            <button type="button" class="calc-btn" onclick="calcAppend('-')" style="background: var(--saffron); color: #FFFFFF;">−</button>

            <!-- Row 4 -->
            <button type="button" class="calc-btn" onclick="calcAppend('1')">1</button>
            <button type="button" class="calc-btn" onclick="calcAppend('2')">2</button>
            <button type="button" class="calc-btn" onclick="calcAppend('3')">3</button>
            <button type="button" class="calc-btn" onclick="calcAppend('+')" style="background: var(--saffron); color: #FFFFFF;">+</button>

            <!-- Row 5 -->
            <button type="button" class="calc-btn" onclick="calcBackspace()" style="background: #475569; color: #FFFFFF;">⌫</button>
            <button type="button" class="calc-btn" onclick="calcAppend('0')">0</button>
            <button type="button" class="calc-btn" onclick="calcAppend('.')">.</button>
            <button type="button" class="calc-btn" onclick="calcEvaluate()" style="background: var(--green); color: #FFFFFF; font-weight: 800;">=</button>
        </div>

        <!-- Keyboard Hint -->
        <div style="margin-top: 18px; text-align: center; font-size: 11.5px; color: #94A3B8; border-top: 1px solid #334155; padding-top: 14px;">
            ⌨️ <strong>Physical Keyboard Active:</strong> Type <code>0-9</code>, <code>+ - * /</code>, <code>Enter / =</code> to calculate, <code>Backspace</code> to delete, <code>Escape</code> to clear.
        </div>
    </div>

</div>

<style>
    .calc-btn {
        background: #1E293B;
        color: #F8FAFC;
        border: 1px solid #334155;
        border-radius: 8px;
        padding: 16px 0;
        font-size: 18px;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Fira Code', monospace;
        transition: all 0.1s ease;
    }
    .calc-btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
    .calc-btn:active {
        transform: translateY(1px);
    }
</style>
@endsection

@section('scripts')
<script>
    let currentInput = "0";
    let history = "";

    function updateScreen() {
        document.getElementById('calcDisplay').innerText = currentInput;
        document.getElementById('calcHistory').innerText = history;
    }

    function calcAppend(val) {
        if (currentInput === "0" && val !== ".") {
            currentInput = val;
        } else {
            currentInput += val;
        }
        updateScreen();
    }

    function calcClear() {
        currentInput = "0";
        history = "";
        updateScreen();
    }

    function calcBackspace() {
        if (currentInput.length > 1) {
            currentInput = currentInput.slice(0, -1);
        } else {
            currentInput = "0";
        }
        updateScreen();
    }

    function calcEvaluate() {
        try {
            history = currentInput + " =";
            // Safe evaluation of arithmetic expressions
            let sanitized = currentInput.replace(/[^0-9+\-*/().]/g, '');
            let result = Function('"use strict";return (' + sanitized + ')')();
            if (!isFinite(result)) {
                currentInput = "Error: Div by 0";
            } else {
                currentInput = String(Number(result.toFixed(6)));
            }
        } catch (e) {
            currentInput = "Syntax Error";
        }
        updateScreen();
    }

    // Keyboard event listener
    document.addEventListener('keydown', function(e) {
        const key = e.key;
        if ((key >= '0' && key <= '9') || ['+', '-', '*', '/', '.', '(', ')'].includes(key)) {
            e.preventDefault();
            calcAppend(key);
        } else if (key === 'Enter' || key === '=') {
            e.preventDefault();
            calcEvaluate();
        } else if (key === 'Backspace') {
            e.preventDefault();
            calcBackspace();
        } else if (key === 'Escape' || key === 'c' || key === 'C') {
            e.preventDefault();
            calcClear();
        }
    });
</script>
@endsection

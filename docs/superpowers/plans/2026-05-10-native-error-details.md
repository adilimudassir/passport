# Native Error Details Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Show full in-app exception details in the packaged NativePHP app instead of a generic 500 page.

**Architecture:** Add a global Laravel exception renderer that detects native-app requests and returns a custom error view with message, file, line, and stack trace. Keep normal logging intact and make the view ergonomic for desktop use, with copy/reload actions.

**Tech Stack:** Laravel 12, NativePHP, Blade, Pest

---

### Task 1: Lock the error rendering behavior with failing tests

**Files:**
- Create: `tests/Feature/NativeErrorRenderingTest.php`
- Test: `tests/Feature/NativeErrorRenderingTest.php`

- [ ] **Step 1: Write the failing test**
- [ ] **Step 2: Run test to verify it fails**
- [ ] **Step 3: Implement the minimal exception rendering code**
- [ ] **Step 4: Run test to verify it passes**

### Task 2: Add the native error details view

**Files:**
- Create: `resources/views/errors/native-debug.blade.php`
- Modify: `bootstrap/app.php`

- [ ] **Step 1: Return the custom error view for native app failures**
- [ ] **Step 2: Build a copyable, scrollable desktop error surface**
- [ ] **Step 3: Verify the test still passes**

### Task 3: Verify full app behavior

**Files:**
- Modify: `tests/Feature/NativeErrorRenderingTest.php`

- [ ] **Step 1: Add coverage for non-native requests if needed**
- [ ] **Step 2: Run full test suite**
- [ ] **Step 3: Run frontend build**

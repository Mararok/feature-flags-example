# Feature flags examples

Requiremnts: 
- php 8.1
- composer
- nodejs min v16
- docker 


# Growthbook
Growthbook is open source Feature Flags provider system.
More info: https://docs.growthbook.io/
## How To run: 
```bash
make init_growthbook
```
// TODO: automatic init, create all

1. Open http://127.0.0.1:3000
2. Create user and login
3. Go to `Features->Environments` and add `test` environment(check option `Show toggle on feature list`)
4. On `Features->Environments` page add `SDK Endpoints` add endpoint for `production` env.
5. Go to `Features->Attributes` and add `QA` as boolean
6. Go to `Features` and add: 
    - Feature Key: `feature_1`
    - Feature Key: `feature_2` 
      - Behavior type `targeted` 
      - Targeting Conditions: `IF QA is true`
      - Value to Force: ON
      - Fallback Value: OFF
7. Click `View instructions` and click again `Features` to see list
8. Go to `Settings->Webhooks` and add 
  - ENVIRONMENT: `production`, endpoint: `http://backend_php_nginx_prod/system/feature-flags`
- ENVIRONMENT: `test`, endpoint: `http://backend_php_nginx_test/system/feature-flags`
# PHP
Simple example of php Symfony app.

**Root App dir:** `app/backend_php`
How to run:
1. Copy `SHARED SECRET` from `Settings->Webhooks` and paste to `.env` and `.env.production` to `GROWTHBOOK_WEBHOOK_SECRET`
2. Copy `Features->Environments->SDK Endpoints` and paste to `.env` and `.env.production` to `GROWTHBOOK_FEATURES_ENDPOINT`
3. Run 
```bash
make init_backend_php
```

## Postman
You can import `postman.json` and test all endpoints.

### Flags cache preview

**DEV:** http://127.0.0.1:30101/system/feature-flags

**PROD:** http://127.0.0.1:30102/system/feature-flags

### Feature examples

**All:**
http://127.0.0.1:30101/feature1

**With QA attribute only:**
Need set cookie `QA=1 lub QA=0` for see effects.

http://127.0.0.1:30101/feature2

# Frontend
Simple Vue3+Vite frontend example.
App updates in background flags state.
**Root App dir:** `app/frontend`
### How to run:
1. Copy client key from `Features->Environments->SDK Endpoints` where `ENVIRONMENT` is `production` to `.env` to `VITE_GROWTHBOOK_CLIENT_KEY`
2. Run
```bash
make init_frontend
```
3. Run vite:
```bash
make frontend_dev
```
4. Click link in Vite console and play witch production environments switches in Growthbook panel

### Refresh flags event listener

You can listen on flags refresh event with: 
```ts
addFeatureFlagRefreshEventListener(function () {
    // your code
});
```

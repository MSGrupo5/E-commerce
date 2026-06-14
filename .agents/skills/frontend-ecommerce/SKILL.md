---
name: frontend-ecommerce
description: >
  Usar SIEMPRE al crear o editar vistas Blade, componentes UI, o cualquier
  elemento visual del proyecto. Define el sistema de tokens, tipografía,
  patrones de componentes y reglas de estilo del e-commerce MSGrupo5.
---

# Frontend Design — E-commerce MSGrupo5

## Identidad visual

**Estética:** Dark tech / hardware store. Fondo casi negro con acentos violeta
eléctrico y cyan. Sensación de premium, sin ser recargado.

**No usar:** fondos claros, estilos "Bootstrap clásico", grises neutros como
color principal, bordes cuadrados (siempre usar border-radius generoso).

---

## Tokens de color (definidos en `tailwind.config.js`)

Tokens confirmados en `tailwind.config.js` (fuente: AGENTS.md):

```js
background: "#0b0b0f"   // fondo de página y contenedores profundos
surface:    "#141419"   // cards, paneles, sidebars
border:     "#1e1e2a"   // bordes de cards y divisores
primary:    "#6c63ff"   // violeta — acción principal, botones CTA, links activos
text:       "#f1f5f9"   // texto principal
muted:      "#94a3b8"   // texto secundario, placeholders, labels
error:      "#ef4444"   // errores, destructivo, sin stock
```

**Cómo usarlos en Tailwind:**
```
bg-background   bg-surface      bg-primary
text-text       text-muted      text-primary    text-error
border-border   border-primary
```

**Nota**: `accent`, `success` y `warning` NO están confirmados en el config oficial. Si los necesitás, verificar en `tailwind.config.js` antes de usar. Usar valores literales como `text-green-400` o `text-cyan-400` como fallback.

**Patrones de color semitransparente** (muy usados en el proyecto):
```
bg-primary/10    border-primary/40   text-primary/60
bg-error/15      border-error/30     text-error
```

---

## Tipografía

Dos fuentes del proyecto (cargadas desde Google Fonts en `app.css`):

| Rol | Familia | Uso |
|-----|---------|-----|
| Display / headings | `font-oxanium` (Oxanium) | Títulos grandes, precios, números destacados |
| Body / UI | `font-jakarta` (Plus Jakarta Sans) | Texto corrido, labels, navegación |
| Fallback general | `font-sans` (Figtree) | Default de Breeze, usar para texto genérico |

**Escala de tamaños** (definida en config):
```
text-h1 (40px)  text-h2 (32px)  text-h3 (24px)  text-h4 (20px)
text-h5 (16px)  text-h6 (14px)  text-body (16px)
text-small (13px)  text-label (12px)
```

**Patrones frecuentes:**
```blade
{{-- Etiqueta de categoría / badge de texto --}}
<p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-muted">
    Categoría
</p>

{{-- Precio principal --}}
<span class="text-xl font-bold text-primary font-oxanium">
    ${{ number_format($product->price, 0, ',', '.') }}
</span>

{{-- Precio tachado --}}
<span class="text-sm text-muted line-through">${{ $originalPrice }}</span>

{{-- Título de sección --}}
<h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-muted">
    Título
</h3>
```

---

## Espaciado y layout

### Contenedor principal de páginas
```blade
<div class="space-y-6 px-4 sm:px-6 lg:px-0">
    {{-- contenido --}}
</div>
```

### Grid de productos (catálogo)
```blade
<div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 justify-items-stretch">
```

### Layout con sidebar (catálogo con filtros)
```blade
<div class="grid gap-4 lg:grid-cols-[280px_minmax(0,1fr)] lg:items-start">
    <aside class="hidden lg:block">{{-- sidebar --}}</aside>
    <div>{{-- contenido principal --}}</div>
</div>
```

---

## Componentes — patrones visuales

### Card contenedor
```blade
<div class="rounded-[32px] border border-border bg-surface shadow-[0_24px_64px_rgba(0,0,0,0.25)] overflow-hidden transition-all duration-300 hover:border-primary/40 hover:-translate-y-0.5">
```

### Card más simple (paneles, formularios)
```blade
<div class="rounded-3xl border border-border bg-surface p-5 sm:p-6">
```

### Botón primario (violeta, acción CTA)
```blade
<button class="w-full rounded-2xl bg-primary px-4 py-3 text-sm font-semibold text-background transition hover:bg-primary/90">
    Agregar al carrito
</button>
```

### Botón secundario / outline
```blade
<button class="rounded-2xl border border-border bg-surface px-4 py-3 text-sm font-medium text-text transition hover:border-primary/50 hover:bg-primary/5">
    Ver detalle
</button>
```

### Botón deshabilitado
```blade
<button disabled class="w-full rounded-2xl bg-border px-4 py-3 text-sm font-semibold text-muted cursor-not-allowed">
    Sin stock
</button>
```

### Botón destructivo (eliminar)
```blade
<button class="rounded-2xl border border-error/30 bg-error/10 px-4 py-2 text-sm font-medium text-error transition hover:bg-error/20">
    Eliminar
</button>
```

### Badge / pill
```blade
{{-- Badge de categoría --}}
<span class="inline-flex rounded-full border border-border bg-background/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-muted">
    {{ $category }}
</span>

{{-- Badge de acento (nuevo, oferta) --}}
<span class="rounded-full border border-accent/25 bg-accent/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-accent">
    Nuevo
</span>

{{-- Badge de estado (en stock) --}}
<span class="rounded-full border border-success/30 bg-success/10 px-3 py-1 text-xs font-medium text-success">
    En stock
</span>
```

### Input de texto
```blade
<input type="text"
    class="w-full rounded-2xl border border-border bg-surface px-4 py-3 text-sm text-text placeholder-muted transition focus:border-primary/60 focus:outline-none focus:ring-1 focus:ring-primary/30"
    placeholder="Buscar productos...">
```

### Mensaje flash — éxito
```blade
<div class="rounded-2xl border border-success/30 bg-success/10 px-4 py-3 text-sm text-success">
    {{ session('success') }}
</div>
```

### Mensaje flash — error
```blade
<div class="rounded-2xl border border-error/30 bg-error/10 px-4 py-3 text-sm text-error">
    {{ session('error') }}
</div>
```

### Estado vacío (carrito vacío, sin resultados)
```blade
<div class="col-span-full rounded-3xl border border-border bg-surface p-8 text-center">
    <p class="text-base font-medium text-text">No hay productos disponibles.</p>
    <p class="mt-1 text-sm text-muted">Probá con otros filtros o categorías.</p>
</div>
```

### Divisor
```blade
<div class="border-t border-border"></div>
```

### Tabla (para panel admin)
```blade
<div class="overflow-hidden rounded-3xl border border-border bg-surface">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-border">
                <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">
                    Columna
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-border">
            <tr class="transition hover:bg-primary/5">
                <td class="px-6 py-4 text-text">Valor</td>
            </tr>
        </tbody>
    </table>
</div>
```

---

## Alpine.js — patrones frecuentes

### Toggle (mostrar/ocultar)
```blade
<div x-data="{ open: false }">
    <button @click="open = !open">Toggle</button>
    <div x-show="open" x-transition>Contenido</div>
</div>
```

### Cerrar con Escape
```blade
<div x-data="{ open: false }" @keydown.escape.window="open = false">
```

### Contador reactivo (carrito)
```blade
<span x-data="{ count: {{ $cartCount ?? 0 }} }"
      x-text="count"
      class="...">
</span>
```

---

## Reglas de estilo

1. **Border-radius generoso siempre**: `rounded-2xl` para botones e inputs, `rounded-3xl` para panels, `rounded-[32px]` para cards grandes.
2. **No usar colores hardcodeados** — siempre usar los tokens del config (`text-primary`, `bg-surface`, etc.)
3. **Transiciones suaves** en interactivos: `transition` + `duration-300` o `duration-150`
4. **Hover states sutiles**: preferir `hover:bg-primary/10` o `hover:border-primary/50` sobre cambios drásticos
5. **Gradientes solo para overlays de imágenes**: `bg-gradient-to-b from-[#11121a]/40 to-transparent`
6. **Iconos**: usar SVGs inline (patrón ya establecido en product-card), stroke-width="2", tamaño `h-5 w-5` o `h-4 w-4`
7. **Responsive primero desde mobile**: `sm:` para tablets, `lg:` para desktop
8. **No usar clases de Breeze** (`text-gray-*`, `bg-white`, `border-gray-*`) en vistas nuevas — reemplazar con tokens propios

---

## Navegación — qué actualizar al agregar rutas

Cuando se implementen rutas nuevas, actualizar el `navigation.blade.php` para agregar:
- Link al carrito con badge de contador
- Link a "Mis pedidos" para clientes
- Acceso al panel admin para usuarios con `isAdmin()`

El navbar actual usa clases de Breeze (grises). Eventualmente migrar a tokens propios.

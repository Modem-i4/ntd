
<!--
<div id="seasonalWrapper"></div>

<style>
  #seasonalWrapper{
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 9999;
  }
</style>

<script src="https://cdn.jsdelivr.net/npm/seasonalfx@0.2.0/dist/index.umd.min.js"></script>
<script>
  const target = document.getElementById("seasonalWrapper");

  const fx = SeasonalFX.createSeasonalFX({
    target,
    season: "spring",
    intensity: "subtle",
      maxFPS: 60,
    seasonConfig: {
      spring: {
        variant: "softPetals"
      }
    }
  });

  fx.start();
</script>

<script src="https://cdn.jsdelivr.net/npm/@cycjimmy/canvas-snow@3/dist/canvas-snow.umd.min.js"></script>
<script>
  const snow = new CanvasSnow({
    context: "#seasonalWrapper",
    cell: 120,     // кількість "сніжинок"
    width: "100%",
    height: "100%",
  }).init();

  snow.start();
</script>
-->
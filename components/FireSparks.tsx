import React, { useEffect, useRef } from 'react';

interface Spark {
  x: number;
  y: number;
  size: number;
  speedY: number;
  speedX: number;
  alpha: number;
  decay: number;
}

const FireSparks: React.FC = () => {
  const canvasRef = useRef<HTMLCanvasElement>(null);

  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    let animationFrameId: number;
    let sparks: Spark[] = [];

    const resizeCanvas = () => {
      canvas.width = canvas.offsetWidth;
      canvas.height = canvas.offsetHeight;
    };

    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    const createSpark = (): Spark => {
      return {
        x: Math.random() * canvas.width,
        y: canvas.height + Math.random() * 20, // Start slightly below
        size: Math.random() * 2 + 0.5,
        speedY: Math.random() * 1 + 0.5,
        speedX: (Math.random() - 0.5) * 0.5,
        alpha: Math.random() * 0.5 + 0.5,
        decay: Math.random() * 0.005 + 0.002
      };
    };

    // Initialize some sparks
    for (let i = 0; i < 50; i++) {
      sparks.push({
        ...createSpark(),
        y: Math.random() * canvas.height // Scatter initially
      });
    }

    const render = () => {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      
      // Add new spark occasionally
      if (Math.random() < 0.1) {
        sparks.push(createSpark());
      }

      sparks.forEach((spark, index) => {
        spark.y -= spark.speedY;
        spark.x += Math.sin(spark.y * 0.05) * 0.5 + spark.speedX;
        spark.alpha -= spark.decay;

        if (spark.alpha <= 0) {
          sparks.splice(index, 1);
          sparks.push(createSpark());
        } else {
          ctx.beginPath();
          ctx.arc(spark.x, spark.y, spark.size, 0, Math.PI * 2);
          ctx.fillStyle = `rgba(255, ${100 + Math.random() * 100}, 0, ${spark.alpha})`;
          ctx.fill();
          
          // Glow effect
          ctx.shadowBlur = 10;
          ctx.shadowColor = "orange";
        }
      });
      
      // Reset shadow for next frame performance
      ctx.shadowBlur = 0;

      animationFrameId = requestAnimationFrame(render);
    };

    render();

    return () => {
      window.removeEventListener('resize', resizeCanvas);
      cancelAnimationFrame(animationFrameId);
    };
  }, []);

  return (
    <canvas 
      ref={canvasRef} 
      className="absolute inset-0 w-full h-full pointer-events-none z-10 mix-blend-screen opacity-80"
    />
  );
};

export default FireSparks;

import React, { useState } from 'react';
import { Image as ImageIcon, Loader2, Download } from 'lucide-react';
// import { generateFantasyImage } from '../services/geminiService'; // TODO: Implement if needed

interface ImageFactoryProps {
  t: any;
}

const ImageFactory: React.FC<ImageFactoryProps> = ({ t }) => {
  const [prompt, setPrompt] = useState('');
  const [size, setSize] = useState<"1K" | "2K" | "4K">("1K");
  const [loading, setLoading] = useState(false);
  const [imgData, setImgData] = useState<string | null>(null);

  const handleGenerate = async () => {
    if(!prompt) return;
    setLoading(true);
    setImgData(null);
    try {
      // TODO: Implement generateFantasyImage service if needed
      console.log('Generate image:', prompt, size);
      alert("Image generation feature not implemented. Configure Gemini API.");
    } catch (error) {
       alert("Failed to generate image. Ensure you have selected a Paid API Key.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="bg-[#12100e] border border-[#3d3122] p-6 shadow-2xl mt-8">
      <div className="flex items-center gap-3 mb-6 border-b border-[#2e2418] pb-4">
        <div className="w-10 h-10 bg-[#2a2115] rounded-full flex items-center justify-center border border-[#5e4b35]">
            <ImageIcon className="w-5 h-5 text-[#cbb085]" />
        </div>
        <div>
            <h2 className="text-[#cbb085] font-serif text-2xl">{t.title}</h2>
            <p className="text-[#8b7d6b] text-sm">{t.subtitle}</p>
        </div>
      </div>

      <div className="flex flex-col md:flex-row gap-6">
        <div className="flex-1 space-y-4">
             <div>
                <label className="block text-[#8b7d6b] uppercase text-xs font-bold tracking-wider mb-2">{t.promptTitle}</label>
                <textarea
                value={prompt}
                onChange={(e) => setPrompt(e.target.value)}
                className="w-full h-32 bg-[#0a0908] border border-[#4a3b2a] text-[#cbb085] p-3 text-sm focus:outline-none focus:border-[#8a6d45] resize-none"
                placeholder={t.promptPlaceholder}
                />
            </div>

            <div>
                 <label className="block text-[#8b7d6b] uppercase text-xs font-bold tracking-wider mb-2">{t.res}</label>
                 <div className="flex gap-2">
                    {(["1K", "2K", "4K"] as const).map((s) => (
                        <button
                            key={s}
                            onClick={() => setSize(s)}
                            className={`flex-1 py-2 text-xs font-bold border ${size === s ? "bg-[#2a2115] text-[#cbb085] border-[#8a6d45]" : "bg-[#0a0908] text-[#5e4b35] border-[#2e2418]"}`}
                        >
                            {s}
                        </button>
                    ))}
                 </div>
            </div>

            <button
                onClick={handleGenerate}
                disabled={loading || !prompt}
                className="w-full bg-[#3d3122] hover:bg-[#4d3e2b] text-[#cbb085] py-3 font-serif font-bold tracking-widest border border-[#5e4b35] mt-4 flex items-center justify-center gap-2"
            >
                {loading ? <Loader2 className="animate-spin" /> : t.button}
            </button>
        </div>

        <div className="flex-1 bg-[#050403] border border-[#2e2418] min-h-[300px] flex items-center justify-center relative group">
             {imgData ? (
                 <>
                    <img src={imgData} alt="Generated" className="max-w-full max-h-[400px] shadow-lg" />
                    <a 
                        href={imgData} 
                        download={`sabariel-art-${Date.now()}.png`}
                        className="absolute bottom-4 right-4 bg-black/70 hover:bg-black text-white p-2 rounded opacity-0 group-hover:opacity-100 transition-opacity"
                    >
                        <Download className="w-5 h-5" />
                    </a>
                 </>
             ) : (
                <div className="text-[#2e2418] text-center">
                    <p className="font-serif text-xl">{t.empty}</p>
                </div>
             )}
        </div>
      </div>
    </div>
  );
};

export default ImageFactory;

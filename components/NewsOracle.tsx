import React, { useState } from 'react';
import { Search, Loader2, ExternalLink } from 'lucide-react';
// import { searchOracle } from '../services/geminiService'; // TODO: Implement if needed
import { GroundingSource } from '../types';
import { Language } from '../src/i18n';

interface NewsOracleProps {
  t: any;
  lang: Language;
}

const NewsOracle: React.FC<NewsOracleProps> = ({ t, lang }) => {
  const [query, setQuery] = useState('');
  const [result, setResult] = useState<string | null>(null);
  const [sources, setSources] = useState<GroundingSource[]>([]);
  const [loading, setLoading] = useState(false);

  const handleSearch = async () => {
    if (!query) return;
    setLoading(true);
    try {
      // TODO: Implement searchOracle service if needed
      // For now, show a placeholder message
      const languageInstruction = ` (Please answer in ${lang === 'es' ? 'Spanish' : lang === 'pt' ? 'Portuguese' : 'English'})`;
      console.log('Search query:', query + languageInstruction);
      setResult(t.noResult || 'Feature not implemented');
      setSources([]);
    } catch (e) {
      setResult(t.error || 'Error occurred');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="bg-[#12100e] border border-[#3d3122] p-6 rounded-sm shadow-inner relative overflow-hidden group">
      <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#8a6d45] to-transparent opacity-50"></div>
      
      <div className="flex items-center justify-between mb-4 border-b border-[#2e2418] pb-2">
        <h3 className="text-[#cbb085] font-serif text-xl tracking-wider uppercase">
          {t.title}
        </h3>
        <Search className="w-5 h-5 text-[#8b7d6b]" />
      </div>

      <div className="mb-4">
        <p className="text-[#8b7d6b] text-sm mb-2">{t.subtitle}</p>
        <div className="flex gap-2">
          <input
            type="text"
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            className="flex-1 bg-[#0a0908] border border-[#4a3b2a] text-[#cbb085] px-3 py-2 text-sm focus:outline-none focus:border-[#8a6d45]"
            placeholder={t.placeholder}
          />
          <button 
            onClick={handleSearch}
            disabled={loading}
            className="bg-[#3d3122] hover:bg-[#4d3e2b] text-[#cbb085] px-4 py-2 text-sm font-bold border border-[#5e4b35] disabled:opacity-50"
          >
            {loading ? <Loader2 className="animate-spin w-4 h-4" /> : t.button}
          </button>
        </div>
      </div>

      {result && (
        <div className="animate-fade-in bg-[#0f0d0b] p-4 border border-[#2e2418]">
          <div className="prose prose-invert prose-sm max-w-none text-[#a89f91] font-serif leading-relaxed">
            {result}
          </div>
          
          {sources.length > 0 && (
            <div className="mt-4 pt-4 border-t border-[#2e2418]">
              <p className="text-xs text-[#6b5d4b] uppercase tracking-widest mb-2">{t.sources}</p>
              <ul className="space-y-1">
                {sources.map((src, i) => (
                   src.web && (
                    <li key={i}>
                      <a 
                        href={src.web.uri} 
                        target="_blank" 
                        rel="noreferrer"
                        className="text-[#8a6d45] hover:text-[#cbb085] text-xs flex items-center gap-1 transition-colors"
                      >
                        <ExternalLink className="w-3 h-3" />
                        {src.web.title}
                      </a>
                    </li>
                   )
                ))}
              </ul>
            </div>
          )}
        </div>
      )}
    </div>
  );
};

export default NewsOracle;
